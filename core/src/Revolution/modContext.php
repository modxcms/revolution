<?php

namespace MODX\Revolution;

use PDOStatement;
use xPDO\Cache\xPDOCacheManager;
use xPDO\xPDO;

/**
 * Represents a virtual site context within a modX repository.
 *
 * @property string                          $key         The key of the context
 * @property string                          $name
 * @property string                          $description The description of the context
 * @property integer                         $rank
 *
 * @property modContextResource[]            $ContextResources
 * @property modContextSetting[]             $ContextSettings
 * @property Sources\modMediaSourceElement[] $SourceElements
 * @property modAccessContext[]              $Acls
 *
 * @package MODX\Revolution
 */
class modContext extends modAccessibleObject
{
    /**
     * A set of Context keys that are restricted to system use only
     *
     *  @var array RESERVED_KEYS
     */
    public const RESERVED_KEYS = ['mgr', 'web', 'root'];

    /**
     * An array of configuration options for this context
     *
     * @var array $config
     */
    public $config = null;
    /**
     * The alias map for this context
     *
     * @var array $aliasMap
     */
    public $aliasMap = null;
    /**
     * The resource map for all resources in this context
     *
     * @var array $resourceMap
     */
    public $resourceMap = null;
    /**
     * A map of WebLink Resources with their target URLs
     *
     * @var array $webLinkMap
     */
    public $webLinkMap = null;
    /**
     * The event map for all events being executed in this context
     *
     * @var array $eventMap
     */
    public $eventMap = null;
    /**
     * The plugin cache array for all plugins being fired in this context
     *
     * @var array $pluginCache
     */
    public $pluginCache = null;
    /**
     * The key for the cache for this context
     *
     * @var string $_cacheKey
     */
    protected $_cacheKey = '[contextKey]/context';

    /**
     * Prepare and execute a PDOStatement to retrieve data needed for $aliasMap and $resourceMap.
     *
     * @static
     *
     * @param modContext &$context A reference to a specific modContext instance.
     *
     * @return PDOStatement|bool A PDOStatement, prepared and executed, with the map data, or false
     * if the statement could not be prepared or executed.
     */
    public static function getResourceCacheMapStmt(&$context)
    {
        return false;
    }

    /**
     * Prepare and execute a PDOStatement to retrieve data needed for $webLinkMap.
     *
     * @static
     *
     * @param modContext &$context A reference to a specific modContext instance.
     *
     * @return PDOStatement|bool A PDOStatement, prepared and executed, with the map data, or false
     * if the statement could not be prepared or executed.
     */
    public static function getWebLinkCacheMapStmt(&$context)
    {
        return false;
    }

    /**
     * Prepare a context for use.
     *
     * @uses   modCacheManager::generateContext() This method is responsible for
     * preparing the context for use.
     *
     * You can override this behavior here, but you will only need to
     * override the modCacheManager::generateContext() method in most cases
     *
     * @access public
     *
     * @param boolean $regenerate If true, the existing cache file will be ignored
     *                            and regenerated.
     *
     * @return boolean Indicates if the context was successfully prepared.
     */
    public function prepare($regenerate = false, array $options = [])
    {
        $prepared = false;
        if ($this->config === null || $regenerate) {
            if ($this->xpdo->getCacheManager()) {
                $context = [];
                if ($regenerate || !($context = $this->xpdo->cacheManager->get($this->getCacheKey(), [
                        xPDO::OPT_CACHE_KEY => $this->xpdo->getOption('cache_context_settings_key', null,
                            'context_settings'),
                        xPDO::OPT_CACHE_HANDLER => $this->xpdo->getOption('cache_context_settings_handler', null,
                            $this->xpdo->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDO\Cache\xPDOFileCache')),
                        xPDO::OPT_CACHE_FORMAT => (integer)$this->xpdo->getOption('cache_context_settings_format', null,
                            $this->xpdo->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
                    ]))) {
                    $context = $this->xpdo->cacheManager->generateContext($this->get('key'), $options);
                }
                if (!empty($context)) {
                    foreach ($context as $var => $val) {
                        if ($var === 'policies') {
                            $this->setPolicies($val);
                            continue;
                        }
                        $this->$var = $val;
                    }
                    $prepared = true;
                }
            }
        } else {
            $prepared = true;
        }

        return $prepared;
    }

    /**
     * Returns a context-specific setting value.
     *
     * @param string $key     The option key to check.
     * @param string $default A default value to use if not found.
     * @param array  $options An array of additional options to merge over top of
     *                        the context settings.
     *
     * @return mixed The option value or the provided default.
     */
    public function getOption($key, $default = null, $options = null)
    {
        if (is_array($options)) {
            $options = array_merge($this->config, $options);
        } else {
            $options =& $this->config;
        }

        return $this->xpdo->getOption($key, $options, $default);
    }

    /**
     * Returns the file name representing this context in the cache.
     *
     * @access public
     * @return string The cache filename.
     */
    public function getCacheKey()
    {
        if ($this->get('key')) {
            $this->_cacheKey = str_replace('[contextKey]', $this->get('key'), $this->_cacheKey);
        } else {
            $this->_cacheKey = str_replace('[contextKey]', uniqid('ctx_'), $this->_cacheKey);
        }

        return $this->_cacheKey;
    }

    /**
     * Loads the access control policies applicable to this element.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '')
    {
        $policy = [];
        $enabled = true;
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (!is_object($this->xpdo->context) || $context === $this->xpdo->context->get('key')) {
            $enabled = (boolean)$this->xpdo->getOption('access_context_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (boolean)$this->xpdo->contexts[$context]->getOption('access_context_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $c = $this->xpdo->newQuery(modAccessContext::class);
                $c->leftJoin(modAccessPolicy::class, 'Policy');
                $c->select([
                    'modAccessContext.id',
                    'modAccessContext.target',
                    'modAccessContext.principal',
                    'modAccessContext.authority',
                    'modAccessContext.policy',
                    'Policy.data',
                ]);
                $c->where([
                    'modAccessContext.principal_class' => modUserGroup::class,
                    'modAccessContext.target' => $this->get('key'),
                ]);
                $c->sortby('modAccessContext.target,modAccessContext.principal,modAccessContext.authority,modAccessContext.policy');
                $acls = $this->xpdo->getCollection(modAccessContext::class, $c);
                /** @var modAccessContext $acl */
                foreach ($acls as $acl) {
                    $policy[modAccessContext::class][$acl->get('target')][] = [
                        'principal' => $acl->get('principal'),
                        'authority' => $acl->get('authority'),
                        'policy' => $acl->get('data') ? json_decode($acl->get('data'), true) : [],
                    ];
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
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
     *
     * @param integer $id      The id of a resource.
     * @param string  $args    A query string to append to the generated URL.
     * @param mixed   $scheme  The scheme indicates in what format the URL is generated.<br>
     *                         <pre>
     *                         -1 : (default value) URL is relative to site_url
     *                         0 : see http
     *                         1 : see https
     *                         full : URL is absolute, prepended with site_url from config
     *                         abs : URL is absolute, prepended with base_url from config
     *                         http : URL is absolute, forced to http scheme
     *                         https : URL is absolute, forced to https scheme
     *                         </pre>
     * @param array   $options An array of options for generating the Resource URL.
     *
     * @return string The URL for the resource.
     */
    public function makeUrl($id, $args = '', $scheme = -1, array $options = [])
    {
        $url = '';
        $found = false;
        if ($id = intval($id)) {
            if ($this->config === null) {
                $this->prepare();
            }
            if (is_object($this->xpdo->context) && $this->get('key') !== $this->xpdo->context->get('key')) {
                $config = array_merge($this->xpdo->_systemConfig, $this->config, $this->xpdo->_userConfig, $options);
                if ($scheme === -1 || $scheme === '' || strpos($scheme, 'abs') !== false) {
                    $scheme = 'full';
                }
            } else {
                $config = array_merge($this->xpdo->config, $options);
            }

            if ($config['friendly_urls'] == 1) {
                if ((integer)$id === (integer)$config['site_start']) {
                    $alias = ($scheme === '' || $scheme === -1) ? $config['base_url'] : '';
                    $found = true;
                } else {
                    $alias = $this->getResourceURI($id);
                    if (!$alias) {
                        $alias = '';
                        $this->xpdo->log(xPDO::LOG_LEVEL_WARN, '`' . $id . '` was requested but no alias was located.');
                    } else {
                        $found = true;
                    }
                }
            } elseif (array_keys([(string)$id], $this->resourceMap, true) !== false) {
                $found = true;
            }

            if ($found) {
                $target = null;
                if (isset($config['use_weblink_target']) && !empty($config['use_weblink_target'])) {
                    if (array_key_exists($id, $this->webLinkMap)) {
                        $target = $this->webLinkMap[$id];
                        if (!empty($target)) {
                            $alias = $target;
                        }
                    }
                }
                $targetHasQS = (empty($config['friendly_urls']) || strpos($alias, '?') !== false);
                if (is_array($args)) {
                    $args = modX::toQueryString($args);
                }
                if ($args != '') {
                    if (!$targetHasQS) {
                        /* add ? to $args if missing */
                        $c = substr($args, 0, 1);
                        if ($c == '&') {
                            $args = '?' . substr($args, 1);
                        } elseif ($c != '?') {
                            $args = '?' . $args;
                        }
                    } elseif ($args != '') {
                        /* add & to $args if missing */
                        $c = substr($args, 0, 1);
                        if ($c == '?') {
                            $args = '&' . substr($args, 1);
                        } elseif ($c != '&') {
                            $args = '&' . $args;
                        }
                    }
                }
                if ($config['friendly_urls'] == 1 || $target !== null) {
                    $url = $alias . $args;
                } else {
                    $url = $config['request_controller'] . '?' . $config['request_param_id'] . '=' . $id . $args;
                }

                $host = '';
                if ($target === null && $scheme !== -1 && $scheme !== '') {
                    if ($scheme === 1 || $scheme === 0) {
                        $https_port = $this->getOption('https_port', $config, 443);
                        $isSecure = ($_SERVER['SERVER_PORT'] == $https_port || (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) ? 1 : 0;
                        if ($scheme != $isSecure) {
                            $scheme = $isSecure ? 'http' : 'https';
                        }
                    }
                    switch ($scheme) {
                        case 'full':
                            $host = $config['site_url'];
                            break;
                        case 'abs':
                        case 'absolute':
                            $host = $config['base_url'];
                            break;
                        case 'https':
                        case 'http':
                            $host = $scheme . '://' . $config['http_host'] . $config['base_url'];
                            break;
                    }
                    $url = $host . $url;
                }
            } else {
                $this->xpdo->log(
                    xPDO::LOG_LEVEL_INFO,
                    "Resource with id {$id} was not found in context {$this->key}",
                    '',
                    __METHOD__,
                    $this->xpdo->resource ? "resource {$this->xpdo->resource->id}" : __FILE__,
                    $this->xpdo->resource ? '' : __LINE__
                );
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
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextBeforeRemove', [
                'context' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        $removed = parent:: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextRemove', [
                'context' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        return $removed;
    }

    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextBeforeSave', [
                'context' => &$this,
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent:: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnContextSave', [
                'context' => &$this,
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        return $saved;
    }

    /**
     * Get and execute a PDOStatement representing data for the aliasMap and resourceMap.
     *
     * @return PDOStatement|null
     */
    public function getResourceCacheMap()
    {
        return $this->xpdo->call(modContext::class, 'getResourceCacheMapStmt', [&$this]);
    }

    /**
     * Get and execute a PDOStatement representing data for the webLinkMap.
     *
     * @return PDOStatement|null
     */
    public function getWebLinkCacheMap()
    {
        return $this->xpdo->call(modContext::class, 'getWebLinkCacheMapStmt', [&$this]);
    }

    /**
     * Get a Resource URI in this Context by id.
     *
     * @param string|integer $id The integer id of the Resource.
     *
     * @return string|bool The URI of the Resource, or false if not found in this Context.
     */
    public function getResourceURI($id)
    {
        if ($this->getOption('cache_alias_map') && isset($this->aliasMap)) {
            $uri = array_search($id, $this->aliasMap);
        } else {
            $query = $this->xpdo->newQuery(modResource::class, [
                'id' => $id,
                'deleted' => false,
                'context_key' => $this->get('key'),
            ]);
            $query->select($this->xpdo->getSelectColumns(modResource::class, '', '', ['uri']));
            $uri = $this->xpdo->getValue($query->prepare());
        }

        return $uri;
    }
}
