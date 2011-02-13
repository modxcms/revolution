<?php
/**
 * Contains the xPDOCacheManager implementation for MODx.
 * @package modx
 */

/**
 * The default xPDOCacheManager instance for MODx.
 *
 * Through this class, MODx provides several types of default, file-based
 * caching to reduce load and dependencies on the database, including:
 * <ul>
 * <li>partial modResource caching, which stores the object properties,
 * along with individual modElement cache items</li>
 * <li>full caching of modContext and modSystemSetting data</li>
 * <li>object-level caching</li>
 * <li>db query-level caching</li>
 * <li>optional JSON object caching for increased Ajax performance
 * possibilities</li>
 * </ul>
 *
 * @package modx
 */
class modCacheManager extends xPDOCacheManager {
    public $modx= null;

    function __construct(& $xpdo, array $options = array()) {
        parent :: __construct($xpdo, $options);
        $this->modx =& $this->xpdo;
    }

    /**
     * Generates a cache entry for a MODx site Context.
     *
     * Context cache entries can override site configuration settings and are responsible for
     * loading the various lisitings and maps in the modX class, including documentListing,
     * documentMap, and eventMap.  It can also be used to setup or transform any other modX
     * properties.
     *
     * @todo Refactor the generation of documentMap, aliasMap, and
     * resourceListing so it uses less memory/file size.
     *
     * @param modContext $obj  The modContext instance to be cached.
     * @param array $options Options for system settings generation.
     * @return array An array containing all the context variable values.
     */
    public function generateContext($key, array $options = array()) {
        $results = array();
        $obj= $this->modx->getObject('modContext', $key, true);
        if (is_object($obj) && $obj instanceof modContext && $obj->get('key')) {
            $contextConfig= $this->modx->_systemConfig;

            /* generate the ContextSettings */
            $results['config']= array();
            if ($settings= $obj->getMany('ContextSettings')) {
                foreach ($settings as $setting) {
                    $k= $setting->get('key');
                    $v= $setting->get('value');
                    $matches = array();
                    if (preg_match_all('~\{(.*?)\}~', $v, $matches, PREG_SET_ORDER)) {
                        foreach ($matches as $match) {
                            if (array_key_exists("{$match[1]}", $contextConfig)) {
                                $matchValue= $contextConfig["{$match[1]}"];
                            } else {
                                $matchValue= '';
                            }
                            $v= str_replace($match[0], $matchValue, $v);
                        }
                    }
                    $results['config'][$k]= $v;
                    $contextConfig[$k]= $v;
                }
            }

            /* generate the documentMap, aliasMap, and resourceListing */
            $tblResource= $this->modx->getTableName('modResource');
            $tblContextResource= $this->modx->getTableName('modContextResource');
            $resourceFields= 'id,parent,alias,isfolder,content_type';
            if (isset ($contextConfig['cache_context_resourceFields']) && $contextConfig['cache_context_resourceFields']) {
                $resourceFields= $contextConfig['cache_context_resourceFields'];
            }
            $resourceCols= $this->modx->getSelectColumns('modResource', 'r', '', explode(',', $resourceFields));
            $bindings= array (
                ':context_key1' => array('value' => $obj->get('key'), 'type' => PDO::PARAM_STR)
                ,':context_key2' => array('value' => $obj->get('key'), 'type' => PDO::PARAM_STR)
            );
            $criteria= new xPDOCriteria($this->modx, "SELECT {$resourceCols} FROM {$tblResource} `r` LEFT JOIN {$tblContextResource} `cr` ON `cr`.`context_key` = :context_key1 AND `r`.`id` = `cr`.`resource` WHERE `r`.`id` != `r`.`parent` AND (`r`.`context_key` = :context_key2 OR `cr`.`context_key` IS NOT NULL) AND `r`.`deleted` = 0 GROUP BY `r`.`id` ORDER BY `r`.`parent` ASC, `r`.`menuindex` ASC", $bindings, false);
            if (!$collContentTypes= $this->modx->getCollection('modContentType')) {
                $htmlContentType= $this->modx->newObject('modContentType');
                $htmlContentType->set('name', 'HTML');
                $htmlContentType->set('description', 'HTML content');
                $htmlContentType->set('mime_type', 'text/html');
                $htmlContentType->set('file_extensions', 'html,htm');
                $collContentTypes['1']= $htmlContentType;
            }
            $collResources= null;
            if ($criteria->prepare() && $criteria->stmt->execute()) {
                $collResources= & $criteria->stmt;
            }
            if ($collResources) {
                $results['resourceMap']= array ();
                $results['resourceListing']= array ();
                $results['aliasMap']= array ();
                $results['documentMap']= array ();
                $containerSuffix= isset ($contextConfig['container_suffix']) ? $contextConfig['container_suffix'] : '';
                $parentPaths= array();
                $parentSql= "SELECT {$resourceCols} FROM {$tblResource} r WHERE r.id = :parent AND r.id != r.parent";
                while ($r = $collResources->fetch(PDO::FETCH_OBJ)) {
                    $parentId= isset($r->parent) ? strval($r->parent) : "0";
                    $results['documentMap'][]= array("{$parentId}" => (string) $r->id);
                    $results['resourceMap']["{$parentId}"][] = (string) $r->id;
                    $resourceValues= get_object_vars($r);
                    $results['resourceListing'][(string) $r->id]= $resourceValues;
                    $resAlias= '';
                    $resPath= '';
                    $contentType= isset ($collContentTypes[$r->content_type]) ? $collContentTypes[$r->content_type] : $collContentTypes['1'];
                    if ((isset ($obj->config['friendly_urls']) && $obj->config['friendly_urls']) || $contextConfig['friendly_urls']) {
                        if ((isset ($obj->config['friendly_alias_urls']) && $obj->config['friendly_alias_urls']) || $contextConfig['friendly_alias_urls']) {
                            $resAlias= $r->alias;
                            if (empty ($resAlias)) $resAlias= $r->id;
                            $parentResource= '';
                            if ((isset ($obj->config['use_alias_path']) && $obj->config['use_alias_path'] == 1) || $contextConfig['use_alias_path']) {
                                $pathParentId= $parentId;
                                $parentResources= array ();
                                $currResource= $r;
                                $hasParent= (boolean) $pathParentId;
                                if ($hasParent) {
                                    if (array_key_exists($parentId, $parentPaths)) {
                                        $resPath= $parentPaths[$parentId];
                                    } else {
                                        if ($parentStmt= $this->modx->prepare($parentSql)) {
                                            $parentStmt->bindParam(':parent', $pathParentId);
                                            if ($parentStmt->execute()) {
                                                while ($hasParent && $currResource= $parentStmt->fetch(PDO::FETCH_OBJ)) {
                                                    $parentAlias= $currResource->alias;
                                                    if (empty ($parentAlias)) {
                                                        $parentAlias= "{$pathParentId}";
                                                    }
                                                    $parentResources[]= "{$parentAlias}";
                                                    $parentPaths[$pathParentId] = implode('/', array_reverse($parentResources));
                                                    $pathParentId= $currResource->parent;
                                                    $hasParent= ($pathParentId > 0 && $parentStmt->execute());
                                                }
                                            }
                                        }
                                        $resPath= !empty ($parentResources) ? implode('/', array_reverse($parentResources)) : '';
                                        $parentPaths[$parentId]= $resPath;
                                    }
                                }
                            }
                        } else {
                            $resAlias= $r->id;
                        }
                        if (!empty($containerSuffix) && $r->isfolder) {
                            $resourceExt= $containerSuffix;
                        } else {
                            $resourceExt= $contentType->getExtension();
                        }
                        if (!empty($resourceExt)) {
                            $resAlias .= $resourceExt;
                        }
                    } else {
                        $resAlias= $r->id;
                    }
                    $results['resourceListing'][(string) $r->id]['path']= $resPath;
                    if (!empty ($resPath)) {
                        $resPath .= '/';
                    }
                    if (isset ($results['aliasMap'][$resPath . $resAlias])) {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Resource alias {$resPath}{$resAlias} already exists for resource id = {$results['aliasMap'][$resPath . $resAlias]}; skipping duplicate resource alias for resource id = {$r->id}");
                        continue;
                    }
                    $results['aliasMap'][$resPath . $resAlias]= $r->id;
                }
            }

            /* generate the eventMap and pluginCache */
            $results['eventMap'] = array();
            $results['pluginCache'] = array();
            $eventMap= $this->modx->getEventMap($obj->get('key'));
            if (is_array ($eventMap) && !empty($eventMap)) {
                $results['eventMap'] = $eventMap;
                $pluginIds= array();
                $plugins= array();
                $this->modx->loadClass('modScript');
                foreach ($eventMap as $pluginKeys) {
                    foreach ($pluginKeys as $pluginKey) {
                        if (isset ($pluginIds[$pluginKey])) {
                            continue;
                        }
                        $pluginIds[$pluginKey]= $pluginKey;
                    }
                }
                if (!empty($pluginIds)) {
                    $pluginQuery = $this->modx->newQuery('modPlugin', array('id:IN' => array_keys($pluginIds)), true);
                    $pluginQuery->select($this->modx->getSelectColumns('modPlugin', 'modPlugin'));
                    if ($pluginQuery->prepare() && $pluginQuery->stmt->execute()) {
                        $plugins= $pluginQuery->stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                }
                if (!empty($plugins)) {
                    foreach ($plugins as $plugin) {
                        $results['pluginCache'][(string) $plugin['id']]= $plugin;
                    }
                }
            }
            if ($this->getOption('cache_context_settings', $options, true)) {
                $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_context_settings_key', $options, 'context_settings');
                $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_context_settings_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
                $options['format'] = (integer) $this->getOption('cache_context_settings_format', $options, xPDOCacheManager::CACHE_SERIALIZE);
                $lifetime = intval($this->getOption('cache_context_settings_expires', $options, 0));
                if (!$this->set($obj->getCacheKey(), $results, $lifetime, $options)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not cache context settings for ' . $obj->get('key') . '.');
                }
            }
        }
        return $results;
    }

    /**
     * Generates the system settings cache for a MODx site.
     *
     * @param array $options Options for system settings generation.
     * @return array The generated system settings array.
     */
    public function generateConfig(array $options = array()) {
        $config = array();
        if ($collection= $this->modx->getCollection('modSystemSetting')) {
            foreach ($collection as $setting) {
                $k= $setting->get('key');
                $v= $setting->get('value');
                $matches= array();
                if (preg_match_all('~\{(.*?)\}~', $v, $matches, PREG_SET_ORDER)) {
                    $matchValue= '';
                    foreach ($matches as $match) {
                        if (isset ($this->modx->config["{$match[1]}"])) {
                            $matchValue= $this->modx->config["{$match[1]}"];
                        } else {
                            /* this causes problems with JSON in settings, disabling for now */
                            //$matchValue= '';
                        }
                        if (!empty($matchValue)) {
                            $v= str_replace($match[0], $matchValue, $v);
                        }
                    }
                }
                $config[$k]= $v;
            }
        }
        if (!empty($config) && $this->getOption('cache_system_settings', $options, true)) {
            $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_system_settings_key', $options, 'system_settings');
            $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_system_settings_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
            $lifetime = intval($this->getOption('cache_system_setting_expires', $options, 0));
            if (!$this->set('config', $config, $lifetime, $options)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not cache system settings.');
            }
        }
        return $config;
    }

    /**
     * Generates a cache entry for a Resource or Resource-derived object.
     *
     * Resource classes can define their own cacheKey.
     *
     * @param modResource $obj  The Resource instance to be cached.
     * @param array $options Options for resource generation.
     * @return array The generated resource representation.
     */
    public function generateResource(modResource & $obj, array $options = array()) {
        $results= array();
        if ($this->getOption('cache_resource', $options, true)) {
            if (is_object($obj) && $obj instanceof modResource && $obj->getProcessed() && $obj->get('cacheable') && $obj->get('id')) {
                $results['resourceClass']= $obj->_class;
                $results['resource']= $obj->toArray('', true);
                $results['resource']['_content']= $obj->_content;
                $results['resource']['_processed']= $obj->getProcessed();
                if ($contentType = $obj->getOne('ContentType')) {
                    $results['contentType']= $contentType->toArray('', true);
                }
                /* TODO: remove legacy docGroups and cache ABAC policies instead */
                if ($docGroups= $obj->getMany('ResourceGroupResources')) {
                    $groups= array();
                    foreach ($docGroups as $docGroupPk => $docGroup) {
                        $groups[(string) $docGroupPk] = $docGroup->toArray('', true);
                    }
                    $results['resourceGroups']= $groups;
                }
                $context = $obj->_contextKey ? $obj->_contextKey : 'web';
                $policies = $obj->findPolicy($context);
                if (!empty($policies)) {
                    $results['policyCache']= $policies;
                }
                if (!empty($this->modx->elementCache)) {
                    $results['elementCache']= $this->modx->elementCache;
                }
                if (!empty($obj->_sjscripts)) {
                    $results['resource']['_sjscripts']= $obj->_sjscripts;
                }
                if (!empty($obj->_jscripts)) {
                    $results['resource']['_jscripts']= $obj->_jscripts;
                }
                if (!empty($obj->_loadedjscripts)) {
                    $results['resource']['_loadedjscripts']= $obj->_loadedjscripts;
                }
            }
            $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_resource_key', $options, 'resource');
            $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_resource_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
            $lifetime = intval($this->getOption('cache_resource_expires', $options, $this->getOption(xPDO::OPT_CACHE_EXPIRES, $options, 0)));
            if (empty($results) || !$this->set($obj->getCacheKey(), $results, $lifetime, $options)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error caching resource " . $obj->get('id'));
            }
        }
        return $results;
    }

    /**
     * Generates a lexicon topic cache file from a collection of entries
     *
     * @access public
     * @param string $cacheKey The key to use when caching the lexicon topic.
     * @param array $entries An array of key => value pairs of lexicon entries.
     * @param array $options An optional array of caching options.
     * @return array An array representing the lexicon topic cache.
     */
    public function generateLexiconTopic($cacheKey, $entries = array(), $options = array()) {
        if (!empty($entries) && $this->getOption('cache_lexicon_topics', $options, true)) {
            $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_lexicon_topics_key', $options, 'lexicon_topics');
            $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_lexicon_topics_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
            $lifetime = intval($this->getOption('cache_lexicon_topics_expires', $options, 0));
            if (!$this->set($cacheKey, $entries, $lifetime, $options)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error caching lexicon topic " . $cacheKey);
            }
        }
        return $entries;
    }

     /**
     * Generates a cache file for the manager actions.
     *
     * @access public
     * @param string $cacheKey The key to use when caching the action map.
     * @return array An array representing the action map.
     */
    public function generateActionMap($cacheKey, array $options = array()) {
        $results= array();
        $c = $this->modx->newQuery('modAction');
        $c->select(array(
            $this->modx->getSelectColumns('modAction', 'modAction'),
            $this->modx->getSelectColumns('modNamespace', 'Namespace', 'namespace_', array('name','path'))
        ));
        $c->innerJoin('modNamespace','Namespace');
        $c->sortby('namespace','ASC');
        $c->sortby('controller','ASC');
        if ($c->prepare() && $c->stmt->execute()) {
            $actions = $c->stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($actions as $action) {
                if (empty($action['namespace_path']) || $action['namespace_name'] == 'core') {
                    $action['namespace_path'] = $this->modx->getOption('manager_path');
                }

                if ($action['namespace_name'] != 'core') {
                    $nsPath = $action['namespace_path'];
                    if (!empty($nsPath)) {
                        $nsPath = str_replace(array(
                            '{core_path}',
                            '{base_path}',
                            '{assets_path}',
                        ),array(
                            $this->modx->getOption('core_path'),
                            $this->modx->getOption('base_path'),
                            $this->modx->getOption('assets_path'),
                        ),$nsPath);
                        $action['namespace_path'] = $nsPath;
                    }
                }
                $results[$action['id']] = $action;
            }
        }
        if (!empty($results) && $this->getOption('cache_action_map', $options, true)) {
            $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_action_map_key', $options, 'action_map');
            $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_action_map_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
            $lifetime = intval($this->getOption('cache_action_map_expires', $options, 0));
            if (!$this->set($cacheKey, $results, $lifetime, $options)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error caching action map {$cacheKey}");
            }
        }
        return $results;
    }

    /**
     * Generates a file representing an executable modScript function.
     *
     * @param modScript $objElement A {@link modScript} instance to generate the
     * script file for.
     * @param string $objContent Optional script content to override the
     * persistent instance.
     * @param array $options An array of additional options for the operation.
     * @return boolean|string The actual generated source content representing the modScript or
     * false if the source content could not be generated.
     */
    public function generateScript(modScript &$objElement, $objContent= null, array $options= array()) {
        $results= false;
        if (is_object($objElement) && $objElement instanceof modScript) {
            $scriptContent= $objElement->getContent(is_string($objContent) ? array('content' => $objContent) : array());
            $scriptName= $objElement->getScriptName();

            $content = "function {$scriptName}(\$scriptProperties= array()) {\n";
            $content .= "global \$modx;\n";
            $content .= "if (is_array(\$scriptProperties)) {\n";
            $content .= "extract(\$scriptProperties, EXTR_SKIP);\n";
            $content .= "}\n";
            $content .= $scriptContent . "\n";
            $content .= "}\n";
            if ($this->getOption('returnFunction', $options, false)) {
                return $content;
            }
            $results = $content;
            if ($this->getOption('cache_scripts', $options, true)) {
                $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_scripts_key', $options, 'scripts');
                $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_scripts_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
                $lifetime = intval($this->getOption('cache_scripts_expires', $options, 0));
                if (empty($results) || !$this->set($objElement->getScriptCacheKey(), $results, $lifetime, $options)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Error caching script " . $objElement->getScriptCacheKey());
                }
            }
        }
        return $results;
    }

    /**
     * Implements MODx cache refresh process, converting cache partitions to cache providers.
     */
    public function refresh(array $providers = array(), array &$results = array()) {
        if (empty($providers)) {
            $contexts = array();
            $query = $this->xpdo->newQuery('modContext');
            $query->select($this->xpdo->escape('key'));
            if ($query->prepare() && $query->stmt->execute()) {
                while ($context = $query->stmt->fetch(PDO::FETCH_COLUMN)) {
                    $contexts[] = $context;
                }
            }
            $providers = array(
                'auto_publish' => array('contexts' => array_diff($contexts, array('mgr'))),
                'system_settings' => array(),
                'context_settings' => array('contexts' => $contexts),
                'db' => array(),
                'scripts' => array(),
                'default' => array(),
                'resource' => array('contexts' => array_diff($contexts, array('mgr'))),
                'menu' => array(),
                'action_map' => array(),
                'lexicon_topics' => array()
            );
        }
        $cleared = array();
        foreach ($providers as $partition => $partOptions) {
            $partKey = $this->xpdo->getOption("cache_{$partition}_key", $partOptions, $partition);
            if (array_search($partKey, $cleared) !== false) {
                $results[$partition] = false;
                continue;
            }
            $partHandler = $this->xpdo->getOption("cache_{$partition}_handler", $partOptions, $this->xpdo->getOption(xPDO::OPT_CACHE_HANDLER));
            if (!is_array($partOptions)) $partOptions = array();
            $partOptions = array_merge($partOptions, array(xPDO::OPT_CACHE_KEY => $partKey, xPDO::OPT_CACHE_HANDLER => $partHandler));
            switch ($partition) {
                case 'auto_publish':
                    $results['auto_publish'] = $this->autoPublish($partOptions);
                    break;
                case 'system_settings':
                    $results['system_settings'] = ($this->generateConfig($partOptions) ? true : false);
                    break;
                case 'context_settings':
                    if (array_key_exists('contexts', $partOptions)) {
                        $contextResults = array();
                        foreach ($partOptions['contexts'] as $context) {
                            $contextResults[$context] = ($this->generateContext($context) ? true : false);
                        }
                        $results['context_settings'] = $contextResults;
                    } else {
                        $results['context_settings'] = false;
                    }
                    break;
                case 'scripts':
                    /* clean the configurable source cache and remove the include files */
                    $results[$partition] = $this->clean($partOptions);
                    $this->deleteTree($this->getCachePath() . 'includes/');
                    break;
                default:
                    $results[$partition] = $this->clean($partOptions);
                    break;
            }
            $cleared[] = $partKey;
        }
        return (array_search(false, $results, true) === false);
    }

    /**
     * Check for and process Resources with pub_date or unpub_date set to now or in past.
     *
     * @todo Implement Context-isolated auto-publishing.
     * @param array $options An array of options for the process.
     * @return array An array containing published and unpublished Resource counts.
     */
    public function autoPublish(array $options = array()) {
        $publishingResults= array();
        /* publish and unpublish resources using pub_date and unpub_date checks */
        $tblResource= $this->modx->getTableName('modResource');
        $timeNow= time() + $this->modx->getOption('server_offset_time', null, 0);
        $publishingResults['published']= $this->modx->exec("UPDATE {$tblResource} SET published=1, publishedon={$timeNow} WHERE pub_date IS NOT NULL AND pub_date < {$timeNow} AND pub_date > 0");
        $publishingResults['unpublished']= $this->modx->exec("UPDATE $tblResource SET published=0, publishedon={$timeNow} WHERE unpub_date IS NOT NULL AND unpub_date < {$timeNow} AND unpub_date > 0");

        /* update publish time file */
        $timesArr= array ();
        $minpub= 0;
        $minunpub= 0;
        $sql= "SELECT MIN(pub_date) FROM {$tblResource} WHERE pub_date > ?";
        $stmt= $this->modx->prepare($sql);
        if ($stmt) {
            $stmt->bindValue(1, time());
            if ($stmt->execute()) {
                foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $value) {
                    $minpub= $value[0];
                    unset($value);
                    break;
                }
            } else {
                $publishingResults['errors'][]= $this->modx->lexicon('cache_publish_event_error',array('info' => $stmt->errorInfo()));
            }
        }
        else {
            $publishingResults['errors'][]= $this->modx->lexicon('cache_publish_event_error',array('info' => $sql));
        }
        if ($minpub) $timesArr[]= $minpub;

        $sql= "SELECT MIN(unpub_date) FROM {$tblResource} WHERE unpub_date > ?";
        $stmt= $this->modx->prepare($sql);
        if ($stmt) {
            $stmt->bindValue(1, time());
            if ($stmt->execute()) {
                foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $value) {
                    $minunpub= $value[0];
                    unset($value);
                    break;
                }
            } else {
                $publishingResults['errors'][]= $this->modx->lexicon('cache_unpublish_event_error',array('info' => $stmt->errorInfo()));
            }
        } else {
            $publishingResults['errors'][]= $this->modx->lexicon('cache_unpublish_event_error',array('info' => $sql));
        }
        if ($minunpub) $timesArr[]= $minunpub;

        if (count($timesArr) > 0) {
            $nextevent= min($timesArr);
        } else {
            $nextevent= 0;
        }

        /* cache the time of the next auto_publish event */
        $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_auto_publish_key', $options, 'auto_publish');
        $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_auto_publish_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
        if (!$this->set('auto_publish', $nextevent, 0, $options)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error caching time of next auto publishing event");
            $publishingResults['errors'][]= $this->modx->lexicon('cache_sitepublishing_file_error');
        }

        return $publishingResults;
    }

    /**
     * Clear part or all of the MODx cache.
     *
     * @deprecated Use refresh()
     * @param array $paths An optional array of paths, relative to the cache
     * path, to be deleted.
     * @param array $options An optional associative array of cache clearing options: <ul>
     * <li><strong>objects</strong>: an array of objects or paths to flush from the db object cache</li>
     * <li><strong>extensions</strong>: an array of file extensions to match when deleting the cache directories</li>
     * </ul>
     */
    public function clearCache(array $paths= array(), array $options= array()) {
        $results= array();
        $delObjs= array();
        if ($clearObjects = $this->getOption('objects', $options)) {
            $objectOptions = array_merge($options, array('cache_prefix' => $this->getOption('cache_db_prefix', $options, xPDOCacheManager::CACHE_DIR)));
            /* clear object cache by key, or * = flush entire object cache */
            if (is_array($clearObjects)) {
                foreach ($clearObjects as $key) {
                    if ($this->delete($key, $objectOptions))
                        $delObjs[]= $key;
                }
            }
            elseif (is_string($clearObjects) && $clearObjects == '*') {
                $delObjs= $this->clean($objectOptions);
            }
        }
        $results['deleted_objects']= $delObjs;
        $extensions= $this->getOption('extensions', $options, array('.cache.php'));
        if (empty($paths)) {
            $paths= array('');
        }
        $delFiles= array();
        foreach ($paths as $pathIdx => $path) {
            $deleted= false;
            $abspath= $this->modx->getOption(xPDO::OPT_CACHE_PATH) . $path;
            if (file_exists($abspath)) {
                if (is_dir($abspath)) {
                    $deleted= $this->deleteTree($abspath, array('deleteTop' => false, 'skipDirs' => false, 'extensions' => $extensions));
                } else {
                    if (unlink($abspath)) {
                        $deleted= array($path);
                    }
                }
                if (is_array($deleted))
                    $delFiles= array_merge($delFiles, $deleted);
            }
            if ($path == '') break;
        }
        $results['deleted_files']= $delFiles;
        $results['deleted_files_count']= count($delFiles);

        if (isset($options['publishing']) && $options['publishing']) {
           $results['publishing']= $this->autoPublish($options);
        }

        /* invoke OnCacheUpdate event */
        $this->modx->invokeEvent('OnCacheUpdate', array(
            'results' => $results,
            'paths' => $paths,
            'options' => $options,
        ));

        return $results;
    }
}