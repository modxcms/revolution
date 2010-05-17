<?php
/**
 * Represents a web resource managed by the modX framework.
 *
 * @package modx
 */
class modResource extends modAccessibleSimpleObject {
    /**
     * Represents the cacheable content for a resource.
     *
     * Note that this is not the raw source content, but the content that is the
     * result of processing cacheable tags within the raw source content.
     * @var string
     */
    public $_content= '';
    /**
     * Represents the output the resource produces.
     * @var string
     */
    public $_output= '';
    /**
     * The context the resource is requested from.
     *
     * Note that this is different than the context_key field that describes a
     * primary context for the resource.
     * @var string
     */
    public $_contextKey= null;
    /**
     * Indicates if the resource has already been processed.
     * @var boolean
     */
    protected $_processed= false;
    /**
     * The cache filename for the resource in the context.
     * @var string
     */
    public $_cacheKey= null;
    /**
     * Indicates if the site cache should be refreshed when saving changes.
     * @var boolean
     */
    protected $_refreshCache= true;
    public $_jscripts = array();
    public $_sjscripts = array();
    public $_loadedjscripts = array();

    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_contextKey= isset ($this->xpdo->context) ? $this->xpdo->context->get('key') : 'web';
        $this->_cacheKey= "[contextKey]/resources/[id]";
    }

    /**
     * Process a resource, transforming source content to output.
     *
     * @return string The processed cacheable content of a resource.
     */
    public function process() {
        if (!$this->get('cacheable') || !$this->_processed || !$this->_content) {
            $this->_content= '';
            $this->_output= '';
            $this->xpdo->getParser();
            if ($baseElement= $this->getOne('Template')) {
                if ($baseElement->process()) {
                    $this->_content= $baseElement->_output;
                    $this->_processed= true;
                }
            } else {
                $this->_content= $this->getContent();
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags('', $this->_content, false, false, '[[', ']]', array(), $maxIterations);
                $this->_processed= true;
            }
        }
        $this->mergeKeywords();
        $this->mergeMetatags();
        return $this->_content;
    }

    /**
     * Gets the raw, unprocessed source content for a resource.
     *
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return string The raw source content for the resource.
     */
    public function getContent(array $options = array()) {
        $content = '';
        if (isset($options['content'])) {
            $content = $options['content'];
        } else {
            $content = $this->get('content');
        }
        return $content;
    }

    /**
     * Set the raw source content for this element.
     *
     * @param mixed $content The source content; implementations can decide if
     * it can only be a string, or some other source from which to retrieve it.
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return boolean True indicates the content was set.
     */
    public function setContent($content, array $options = array()) {
        return $this->set('content', $content);
    }

    /**
     * Merge in Keywords into content.
     *
     * @access public
     * @deprecated 2.0
     */
    public function mergeKeywords() {
        if ($this->get('haskeywords')) {
            $keywords = implode(", ",$this->xpdo->getKeywords());
            $metas = "<meta name=\"keywords\" content=\"{$keywords}\" />\n";
            $this->_content = preg_replace("/(<head>)/i", "\\1\n".$metas, $this->_content);
        }
    }

    /**
     * Merge in META tags to content.
     *
     * @access public
     * @deprecated 2.0
     */
    public function mergeMetaTags() {
        if ($this->get('hasmetatags')) {
            if ($tags = $this->xpdo->getMETATags()) {
                foreach ($tags as $n=>$col) {
                    $tag = strtolower($col['tag']);
                    $tagvalue = $col['tagvalue'];
                    $tagstyle = $col['http_equiv'] ? 'http-equiv':'name';
                    $metas.= "\t<meta $tagstyle=\"$tag\" content=\"$tagvalue\" />\n";
                }
                $this->_content = preg_replace("/(<head>)/i", "\\1\n".$metas, $this->_content);
            }
        }
    }

    /**
     * Returns the cache key for this instance in the current context.
     *
     * @return string The cache key.
     */
    public function getCacheKey() {
        $id = $this->get('id') ? (string) $this->get('id') : '0';
        $context = $this->_contextKey ? $this->_contextKey : 'web';
        if (strpos($this->_cacheKey, '[') !== false) {
            $this->_cacheKey= str_replace('[contextKey]', $context, $this->_cacheKey);
            $this->_cacheKey= str_replace('[id]', $id, $this->_cacheKey);
        }
        return $this->_cacheKey;
    }

    /**
     * Gets a collection of objects related by aggregate or composite relations.
     *
     * {@inheritdoc}
     *
     * Includes special handling for related objects with alias {@link
     * modTemplateVar}, respecting framework security unless specific criteria
     * are provided.
     *
     * @todo Refactor to use the new ABAC security model.
     */
    public function & getMany($alias, $criteria= null, $cacheFlag= false) {
        $collection= array ();
        if ($alias === 'TemplateVars' || $alias === 'modTemplateVar' && ($criteria === null || strtolower($criteria) === 'all')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->select('
                DISTINCT modTemplateVar.*,
                IF(ISNULL(tvc.value),modTemplateVar.default_text,tvc.value) AS value,
                IF(ISNULL(tvc.value),0,'.$this->id.') AS resourceId
            ');
            $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
                '`tvtpl`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvtpl`.templateid' => $this->template,
            ));
            $c->leftJoin('modTemplateVarResource','tvc',array(
                '`tvc`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvc`.contentid' => $this->id,
            ));
            $c->sortby('`tvtpl`.`rank`,`modTemplateVar`.`rank`');

            $collection = $this->xpdo->getCollection('modTemplateVar', $c);
        } else {
            $collection= parent :: getMany($alias, $criteria, $cacheFlag);
        }
        return $collection;
    }

    /**
     * Set a field value by the field key or name.
     *
     * {@inheritdoc}
     *
     * Additional logic added for the following fields:
     * 	-alias: Applies {@link modResource::cleanAlias()}
     *  -contentType: Calls {@link modResource::addOne()} to sync contentType
     *  -content_type: Sets the contentType field appropriately
     */
    public function set($k, $v= null, $vType= '') {
        $rt= false;
        switch ($k) {
            case 'alias' :
                $v= $this->cleanAlias($v);
                break;
            case 'contentType' :
                if ($v !== $this->get('contentType')) {
                    if ($contentType= $this->xpdo->getObject('modContentType', array ('mime_type' => $v))) {
                        if ($contentType->get('mime_type') != $this->get('contentType')) {
                            $this->addOne($contentType, 'ContentType');
                        }
                    }
                }
                break;
            case 'content_type' :
                if ($v !== $this->get('content_type')) {
                    if ($contentType= $this->xpdo->getObject('modContentType', $v)) {
                        if ($contentType->get('mime_type') != $this->get('contentType')) {
                            $this->_fields['contentType']= $contentType->get('mime_type');
                            $this->_dirty['contentType']= 'contentType';
                        }
                    }
                }
                break;
        }
        return parent :: set($k, $v, $vType);
    }

    /**
     * Adds an object related to this modResource by a foreign key relationship.
     *
     * {@inheritdoc}
     *
     * Adds legacy support for keeping the existing contentType field in sync
     * when a modContentType is set using this function.
     */
    public function addOne(& $obj, $alias= '') {
        $added= parent :: addOne($obj, $alias);
        if ($obj instanceof modContentType && $alias= 'ContentType') {
            $this->_fields['contentType']= $obj->get('mime_type');
            $this->_dirty['contentType']= 'contentType';
        }
        return $added;
    }

    /**
     * Transforms a string to form a valid URL representation.
     *
     * @param string $alias A string to transform into a valid URL representation.
     * @param array $options Options to append to or override configuration settings.
     * @return string The transformed string.
     */
    public function cleanAlias($alias, array $options = array()) {
        /* setup the various options */
        $iconv = function_exists('iconv');
        $mbext = function_exists('mb_strlen');
        $charset = strtoupper((string) $this->xpdo->getOption('modx_charset', $options, 'UTF-8'));
        $delimiter = $this->xpdo->getOption('friendly_alias_word_delimiter', $options, '-');
        $delimiters = $this->xpdo->getOption('friendly_alias_word_delimiters', $options, '-_');
        $maxlength = (integer) $this->xpdo->getOption('friendly_alias_max_length', $options, 0);
        $stripElementTags = (boolean) $this->xpdo->getOption('friendly_alias_strip_element_tags', $options, true);
        $trimchars = $this->xpdo->getOption('friendly_alias_trim_chars', $options, '/.' . $delimiters);
        $restrictchars = $this->xpdo->getOption('friendly_alias_restrict_chars', $options, 'pattern');
        $restrictcharspattern = $this->xpdo->getOption('friendly_alias_restrict_chars_pattern', $options, '/[\0\x0B\t\n\r\f\a&=+%#<>"~`@\?\[\]\{\}\|\^\'\\\\]/');
        $lowercase = (boolean) $this->xpdo->getOption('friendly_alias_lowercase_only', $options, true);
        $translit = $this->xpdo->getOption('friendly_alias_translit', $options, $iconv ? 'iconv' : 'none');
        $translitClass = $this->xpdo->getOption('friendly_alias_translit_class', $options, 'translit.modTransliterate');

        /* get the strlen of the alias (use mb extension if available) */
        $length = $mbext ? mb_strlen($alias, $charset) : strlen($alias);

        /* strip html and optionally MODx element tags (stripped by default) */
        if ($this->xpdo instanceof modX) {
            $alias = $this->xpdo->stripTags($alias, '', $stripElementTags ? array() : null);
        }

        /* replace &nbsp; with the specified word delimiter */
        $alias = str_replace('&nbsp;', $delimiter, $alias);

        /* decode named entities to the appropriate character for the character set */
        $alias = html_entity_decode($alias, ENT_QUOTES, $charset);

        /* replace any remaining & with a lexicon value if available */
        if ($this->xpdo instanceof modX && $this->xpdo->getService('lexicon','modLexicon')) {
            $alias = str_replace('&', $this->xpdo->lexicon('and') ? ' ' . $this->xpdo->lexicon('and') . ' ' : ' and ', $alias);
        }

        /* apply transliteration as configured */
        if (!($this->xpdo instanceof modX)) { $translit = 'none'; }
        switch ($translit) {
            case '':
            case 'none':
                /* no transliteration */
                break;
            case 'iconv':
                /* if iconv is available, use the built-in transliteration it provides */
                $alias = iconv($charset, 'ASCII//TRANSLIT//IGNORE', $alias);
                break;
            default:
                /* otherwise look for a transliteration service class that will accept named transliteration tables */
                $translitClassPath = $this->xpdo->getOption('friendly_alias_translit_class_path', $options, $this->xpdo->getOption('core_path', $options, MODX_CORE_PATH) . 'components/');
                if ($this->xpdo->getService('translit', $translitClass, $translitClassPath, $options)) {
                    $alias = $this->xpdo->translit->translate($alias, $translit);
                }
        }
        /* restrict characters as configured */
        switch ($restrictchars) {
            case 'alphanumeric':
                /* restrict alias to alphanumeric characters only */
                $alias = preg_replace('/[^\.%A-Za-z0-9 _-]/', '', $alias);
                break;
            case 'alpha':
                /* restrict alias to alpha characters only */
                $alias = preg_replace('/[^\.%A-Za-z _-]/', '', $alias);
                break;
            case 'legal':
                /* restrict alias to legal URL characters only */
                $alias = preg_replace('/[\0\x0B\t\n\r\f\a&=+%#<>"~`@\?\[\]\{\}\|\^\'\\\\]/', '', $alias);
                break;
            case 'pattern':
            default:
                /* restrict alias using regular expression pattern configured (same as legal by default) */
                $alias = preg_replace($restrictcharspattern, '', $alias);
        }
        /* replace one or more space characters with word delimiter */
        $alias = preg_replace('/\s+/', $delimiter, $alias);
        /* replace one or more instances of word delimiters with word delimiter */
        $delimiterTokens = array();
        for ($d = 0; $d < strlen($delimiters); $d++) {
            $delimiterTokens[] = $delimiters{$d};
        }
        $delimiterPattern = '/[' . implode('|', $delimiterTokens) . ']+/';
        $alias = preg_replace($delimiterPattern, $delimiter, $alias);
        /* unless lowercase_only preference is explicitly off, change case to lowercase */
        if ($lowercase) {
            if ($mbext) {
                /* if the mb extension is available use it to protect multi-byte chars */
                $alias = mb_convert_case($alias, MB_CASE_LOWER, $charset);
            } else {
                /* otherwise, just use strtolower */
                $alias = strtolower($alias);
            }
        }
        /* trim specified chars from both ends of the alias */
        $alias = trim($alias, $trimchars);
        /* if maxlength is specified and exceeded, return substr with additional trim applied */
        if ($maxlength > 0 && $length > $maxlength) {
            $alias = substr($alias, 0, $maxlength);
            $alias = trim($alias, $trimchars);
        }
        return $alias;
    }

    /**
     * Persist new or changed modResource instances to the database container.
     *
     * {@inheritdoc}
     *
     * If the modResource is new, the createdon and createdby fields will be set
     * using the current time and user authenticated in the context.
     */
    public function save($cacheFlag= null) {
        if ($this->_new) {
            if (!$this->get('createdon')) $this->set('createdon', time());
            if (!$this->get('createdby') && $this->xpdo instanceof modX) $this->set('createdby', $this->xpdo->getLoginUserID());
        }
        $rt= parent :: save($cacheFlag);
        return $rt;
    }

    /**
     * Return whether or not the resource has been processed.
     *
     * @access public
     */
    public function getProcessed() {
        return $this->_processed;
    }

    /**
     * Adds a lock on the Resource
     *
     * @access public
     * @param integer $user
     * @param array $options An array of options for the lock.
     * @return boolean True if the lock was successful.
     */
    public function addLock($user = 0, array $options = array()) {
        $locked = false;
        if ($this->xpdo instanceof modX) {
            if (!$user) {
                $user = $this->xpdo->user->get('id');
            }
            $lockedBy = $this->getLock();
            if (empty($lockedBy) || ($lockedBy == $user)) {
                $this->xpdo->registry->locks->subscribe('/resource/');
                $this->xpdo->registry->locks->send('/resource/', array(md5($this->get('id')) => $user), array('ttl' => $this->xpdo->getOption('lock_ttl', $options, 360)));
                $locked = true;
            } elseif ($lockedBy != $user) {
                $locked = $lockedBy;
            }
        }
        return $locked;
    }

    /**
     * Gets the lock on the Resource.
     *
     * @access public
     * @return int
     */
    public function getLock() {
        $lock = 0;
        if ($this->xpdo instanceof modX) {
            if ($this->xpdo->getService('registry', 'registry.modRegistry')) {
                $this->xpdo->registry->addRegister('locks', 'registry.modDbRegister', array('directory' => 'locks'));
                $this->xpdo->registry->locks->connect();
                $this->xpdo->registry->locks->subscribe('/resource/' . md5($this->get('id')));
                if ($msgs = $this->xpdo->registry->locks->read(array('remove_read' => false, 'poll_limit' => 1))) {
                    $msg = reset($msgs);
                    $lock = intval($msg);
                }
            }
        }
        return $lock;
    }

    /**
     * Removes all locks on a Resource.
     *
     * @access public
     * @return boolean True if locks were removed.
     */
    public function removeLock($user = 0) {
        $removed = false;
        if ($this->xpdo instanceof modX) {
            if (!$user) {
                $user = $this->xpdo->user->get('id');
            }
            if ($this->xpdo->getService('registry', 'registry.modRegistry')) {
                $this->xpdo->registry->addRegister('locks', 'registry.modDbRegister', array('directory' => 'locks'));
                $this->xpdo->registry->locks->connect();
                $this->xpdo->registry->locks->subscribe('/resource/' . md5($this->get('id')));
                $this->xpdo->registry->locks->read(array('remove_read' => true, 'poll_limit' => 1));
                $removed = true;
            }
        }
        return $removed;
    }

    /**
     * Loads the access control policies applicable to this resource.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessResourceGroup');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $resourceGroupTable = $this->xpdo->getTableName('modResourceGroupResource');
            $sql = "SELECT `Acl`.`target`, `Acl`.`principal`, `Acl`.`authority`, `Acl`.`policy`, `Policy`.`data` FROM {$accessTable} `Acl` " .
                    "LEFT JOIN {$policyTable} `Policy` ON `Policy`.`id` = `Acl`.`policy` " .
                    "JOIN {$resourceGroupTable} `ResourceGroup` ON `Acl`.`principal_class` = 'modUserGroup' " .
                    "AND (`Acl`.`context_key` = :context OR `Acl`.`context_key` IS NULL OR `Acl`.`context_key` = '') " .
                    "AND `ResourceGroup`.`document` = :resource " .
                    "AND `ResourceGroup`.`document_group` = `Acl`.`target` " .
                    "GROUP BY `Acl`.`target`, `Acl`.`principal`, `Acl`.`authority`, `Acl`.`policy`";
            $bindings = array(
                ':resource' => $this->get('id'),
                ':context' => $context
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $policy['modAccessResourceGroup'][$row['target']][] = array(
                        'principal' => $row['principal'],
                        'authority' => $row['authority'],
                        'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                    );
                }
            }
            $this->_policies[$context] = $policy;
        } else {
            $policy = $this->_policies[$context];
        }
        return $policy;
    }

    /**
     * Checks to see if the Resource has children or not. Returns the number of
     * children.
     *
     * @access public
     * @return integer The number of children of the Resource
     */
    public function hasChildren() {
        $c = $this->xpdo->newQuery('modResource');
        $c->where(array(
            'parent' => $this->get('id'),
        ));
        return $this->xpdo->getCount('modResource',$c);
    }

    /**
     * Gets the value of a TV for the Resource.
     *
     * @access public
     * @param mixed $pk Either the ID of the TV, or the name of the TV.
     * @return null/mixed The value of the TV for the Resource, or null if the
     * TV is not found.
     */
    public function getTVValue($pk) {
        if (is_string($pk)) {
            $pk = array('name' => $pk);
        }
        $tv = $this->xpdo->getObject('modTemplateVar',$pk);
        return $tv == null ? null : $tv->renderOutput($this->get('id'));
    }

    /**
     *
     * @return mixed Returns either an error message, or the newly created modResource object.
     */
    public function duplicate(array $options = array()) {
        if (!($this->xpdo instanceof modX)) return false;

        /* duplicate resource */
        $newResource = $this->xpdo->newObject($this->get('class_key'));
        $newResource->fromArray($this->toArray('', true), '', false, true);
        $newResource->set('pagetitle',!empty($options['newName']) ? $options['newName'] : $this->xpdo->lexicon('duplicate_of').$this->get('pagetitle'));
        $newResource->set('alias', null);

        /* make sure children get assigned to new parent */
        $newResource->set('parent',isset($options['parent']) ? $options['parent'] : $this->get('parent'));
        $newResource->set('createdby',$this->xpdo->user->get('id'));
        $newResource->set('createdon',time());
        $newResource->set('editedby',0);
        $newResource->set('editedon',0);
        $newResource->set('deleted',false);
        $newResource->set('deletedon',0);
        $newResource->set('deletedby',0);
        $newResource->set('publishedon',0);
        $newResource->set('publishedby',0);
        $newResource->set('published',false);
        
        if (!$newResource->save()) {
            return $this->xpdo->lexicon('resource_err_duplicate');
        }

        $tvds = $this->getMany('TemplateVarResources');
        foreach ($tvds as $oldTemplateVarResource) {
            $newTemplateVarResource = $this->xpdo->newObject('modTemplateVarResource');
            $newTemplateVarResource->set('contentid',$newResource->get('id'));
            $newTemplateVarResource->set('tmplvarid',$oldTemplateVarResource->get('tmplvarid'));
            $newTemplateVarResource->set('value',$oldTemplateVarResource->get('value'));
            $newTemplateVarResource->save();
        }

        $groups = $this->getMany('ResourceGroupResources');
        foreach ($groups as $oldResourceGroupResource) {
            $newResourceGroupResource = $this->xpdo->newObject('modResourceGroupResource');
            $newResourceGroupResource->set('document_group',$oldResourceGroupResource->get('document_group'));
            $newResourceGroupResource->set('document',$oldResourceGroupResource->get('id'));
            $newResourceGroupResource->save();
        }

        /* duplicate resource, recursively */
        $duplicateChildren = isset($options['duplicateChildren']) ? $options['duplicateChildren'] : true;
        if ($duplicateChildren) {
            if (!$this->checkPolicy('add_children')) return $newResource;
            
            $children = $this->getMany('Children');
            if (is_array($children) && count($children) > 0) {
                foreach ($children as $child) {
                    $child->duplicate(array(
                        'duplicateChildren' => true,
                        'parent' => $newResource->get('id'),
                    ));
                }
            }
        }
        return $newResource;
    }
}