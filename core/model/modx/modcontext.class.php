<?php
/**
 * modContext
 *
 * @package modx
 */
/**
 * Represents a virtual site context within a modX repository.
 *
 * @package modx
 */
class modContext extends modAccessibleObject {
    public $config= null;
    public $aliasMap= null;
    public $resourceMap= null;
    public $resourceListing= null;
    public $documentListing= null;
    public $documentMap= null;
    public $eventMap= null;
    public $pluginCache= null;
    protected $_cacheKey= '[contextKey]/context';

    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->documentListing= & $this->resourceListing;
    }

    /**
     * Prepare a context for use.
     *
     * @uses modCacheManager::generateContext() This method is responsible for
     * preparing the context for use.
     * {@internal You can override this behavior here, but you will only need to
     * override the modCacheManager::generateContext() method in most cases}}
     * @access public
     * @param boolean $regenerate If true, the existing cache file will be ignored
     * and regenerated.
     * @return boolean Indicates if the context was successfully prepared.
     */
    public function prepare($regenerate= false) {
        $prepared= false;
        if ($this->config === null || $regenerate) {
            if ($this->xpdo->getCacheManager()) {
                $context = array();
                if ($regenerate || !($context = $this->xpdo->cacheManager->get($this->getCacheKey()))) {
                    $context = $this->xpdo->cacheManager->generateContext($this->get('key'));
                }
                if (!empty($context)) {
                    foreach ($context as $var => $val) {
                        $this->$var = $val;
                    }
                    $prepared= true;
                }
            }
        } else {
            $prepared= true;
        }
        return $prepared;
    }

    /**
     * Returns the file name representing this context in the cache.
     *
     * @access public
     * @return string The cache filename.
     */
    public function getCacheKey() {
        if ($this->get('key')) {
            $this->_cacheKey= str_replace('[contextKey]', $this->get('key'), $this->_cacheKey);
        } else {
            $this->_cacheKey= str_replace('[contextKey]', uniqid('ctx_'), $this->_cacheKey);
        }
        return $this->_cacheKey;
    }

    /**
     * Loads the access control policies applicable to this element.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessContext');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $sql = "SELECT acl.target, acl.principal, acl.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "WHERE acl.principal_class = 'modUserGroup' " .
                    "AND acl.target = :context " .
                    "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
            $bindings = array(
                ':context' => $this->get('key')
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $policy['modAccessContext'][$row['target']][] = array(
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
     * Generates a URL representing a specified resource in this context.
     *
     * Note that if this method is called from a context other than the one
     * initialized for the modX instance, and the scheme is not specified, an
     * empty string, or abs, the method will force the scheme to full.
     *
     * @access public
     * @param integer $id The id of a resource.
     * @param string $args A query string to append to the generated URL.
     * @param mixed $scheme The scheme indicates in what format the URL is generated.<br>
     * <pre>
     *      -1 : (default value) URL is relative to site_url
     *       0 : see http
     *       1 : see https
     *    full : URL is absolute, prepended with site_url from config
     *     abs : URL is absolute, prepended with base_url from config
     *    http : URL is absolute, forced to http scheme
     *   https : URL is absolute, forced to https scheme
     * </pre>
     * @return string The URL for the resource.
     */
    public function makeUrl($id, $args = '', $scheme = -1) {
        $url = '';
        $found = false;
        if ($id= intval($id)) {
            if (is_object($this->xpdo->context) && $this->get('key') !== $this->xpdo->context->get('key')) {
                $config = array_merge($this->xpdo->_systemConfig, $this->config, $this->xpdo->_userConfig);
                if ($scheme === -1 || $scheme === '' || strpos($scheme, 'abs') !== false) {
                    $scheme= 'full';
                }
            } else {
                $config = $this->xpdo->config;
            }

            if ($config['friendly_urls'] == 1) {
                if ($id == $config['site_start']) {
                    $alias= ($scheme === '' || $scheme === -1) ? $config['base_url'] : '';
                    $found= true;
                } else {
                    $alias= array_search($id, $this->aliasMap);
                    if (!$alias) {
                        $alias= '';
                        $this->xpdo->log(xPDO::LOG_LEVEL_WARN, '`' . $id . '` was requested but no alias was located.');
                    } else {
                        $found= true;
                    }
                }
            } elseif (isset($this->resourceListing["{$id}"])) {
                $found= true;
            }

            if ($found) {
                if (is_array($args)) {
                    $args = modX::toQueryString($args);
                }
                if ($args != '' && $config['friendly_urls'] == 1) {
                    /* add ? to $args if missing */
                    $c= substr($args, 0, 1);
                    if ($c == '&') {
                        $args= '?' . substr($args, 1);
                    } elseif ($c != '?') {
                        $args= '?' . $args;
                    }
                }
                elseif ($args != '') {
                    /* add & to $args if missing */
                    $c= substr($args, 0, 1);
                    if ($c == '?')
                        $args= '&' . substr($args, 1);
                    elseif ($c != '&') $args= '&' . $args;
                }

                if ($config['friendly_urls'] == 1) {
                    $url= $alias . $args;
                } else {
                    $url= $config['request_controller'] . '?' . $config['request_param_id'] . '=' . $id . $args;
                }

                $host= '';
                if ($scheme !== -1 && $scheme !== '') {
                    if ($scheme === 1 || $scheme === 0) {
                        $https_port= $this->xpdo->getOption('https_port',$config,443);
                        $isSecure= ($_SERVER['SERVER_PORT'] == $https_port || (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'])=='on')) ? 1 : 0;
                        if ($scheme != $isSecure) {
                            $scheme = $isSecure ? 'http' : 'https';
                        }
                    }
                    switch ($scheme) {
                        case 'full':
                            $host= $config['site_url'];
                            break;
                        case 'abs':
                        case 'absolute':
                            $host= $config['base_url'];
                            break;
                        case 'https':
                        case 'http':
                            $host= $scheme . '://' . $config['http_host'] . $config['base_url'];
                            break;
                    }
                    $url= $host . $url;
                }
            }
        }
        if ($this->xpdo->getDebug() === true) {
            $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "modContext[" . $this->get('key') . "]->makeUrl({$id}) = {$url}");
        }
        return $url;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events
     * 
     * {@inheritDoc}
     */
    public function remove(array $ancestors = array()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextBeforeRemove',array(
                'context' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextRemove',array(
                'context' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        return $removed;
    }

    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag= null) {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextBeforeSave',array(
                'context' => &$this,
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent :: save($cacheFlag);
        
        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextSave',array(
                'context' => &$this,
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'cacheFlag' => $cacheFlag,
            ));
        }
        return $saved;
    }
}
