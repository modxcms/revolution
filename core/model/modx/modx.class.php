<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 */

/**
 * This is the main file to include in your scripts to use MODx.
 *
 * For detailed information on using this class, see {@tutorial modx/modx.pkg}.
 *
 * @package modx
 */
if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);
}
if (!defined('MODX_CONFIG_KEY')) {
    define('MODX_CONFIG_KEY', 'config');
}
if (!defined('MODX_SESSION_STATE_UNINITIALIZED')) {
    define('MODX_SESSION_STATE_UNAVAILABLE',   -1);
    define('MODX_SESSION_STATE_UNINITIALIZED',  0);
    define('MODX_SESSION_STATE_INITIALIZED',    1);
    define('MODX_SESSION_STATE_EXTERNAL',       2);
}
require_once (MODX_CORE_PATH . 'xpdo/xpdo.class.php');

/**
 * This is the MODx gateway class.
 *
 * It can be used to interact with the MODx framework and serves as a front
 * controller for handling requests to the virtual resources managed by the MODx
 * Content Management Framework.
 *
 * @package modx
 */
class modX extends xPDO {
    /**
     * @var modContext The Context represents a unique section of the site which
     * this modX instance is controlling.
     */
    var $context= null;
    /**
     * @var array An array of secondary contexts loaded on demand.
     */
    var $contexts= array();
    /**
     * @var modRequest Represents a web request and provides helper methods for
     * dealing with request parameters and other attributes of a request.
     */
    var $request= null;
    /**
     * @var modResponse Represents a web response, providing helper methods for
     * managing response header attributes and the body containing the content of
     * the response.
     */
    var $response= null;
    /**
     * @var modParser The modParser registered for this modX instance,
     * responsible for content tag parsing, and loaded only on demand.
     */
    var $parser= null;
    /**
     * @var array An array of supplemental service classes for this modX instance.
     */
    var $services= array ();
    /**
     * @var array A listing of site Resources and Context-specific meta data.
     */
    var $resourceListing= null;
    /**
     * @var array A hierarchy map of Resources.
     */
    var $resourceMap= null;
    /**
     * @var array A lookup listing of Resource alias values and associated
     * Resource Ids
     */
    var $aliasMap= null;
    /**
     * @var modEvent The current event being handled by modX.
     */
    var $event= null;
    /**
     * @var array A map of elements registered to specific events.
     */
    var $eventMap= null;
    /**
     * @var array A map of actions registered to the manager interface.
     */
    var $actionMap= null;
    /**
     * @var array A map of already processed Elements.
     */
    var $elementCache= array ();
    /**
     * @var array An array of key=> value pairs that can be used by any Resource
     * or Element.
     */
    var $placeholders= array ();
    /**
     * @var modResource An instance of the current modResource controlling the
     * request.
     */
    var $resource= null;
    /**
     * @var string The preferred Culture key for the current request.
     */
    var $cultureKey= 'en';
    /**
     * @var array Represents a localized dictionary of common words and phrases.
     */
    var $lexicon= null;
    /**
     * @var modUser The current user object, if one is authenticated for the
     * current request and context.
     */
    var $user= null;
    /**
     * @var array Represents the modContentType instances that can be delivered
     * by this modX deployment.
     */
    var $contentTypes= null;
    /**
     * @var mixed The resource id or alias being requested.
     */
    var $resourceIdentifier= null;
    /**
     * @var string The method to use to locate the Resource, 'id' or 'alias'.
     */
    var $resourceMethod= null;
    /**
     * @var boolean Indicates if the resource was generated during this request.
     */
    var $resourceGenerated= true;
    /**
     * @var array Version information for this MODx deployment.
     */
    var $version= null;
    /**
     * @var boolean Indicates if modX has been successfully initialized for a
     * modContext.
     */
    var $_initialized= false;
    /**
     * @var array An array of javascript content to be inserted into the HEAD
     * of an HTML resource.
     */
    var $sjscripts= array ();
    /**
     * @var array An array of javascript content to be inserted into the BODY
     * of an HTML resource.
     */
    var $jscripts= array ();
    var $loadedjscripts= array ();
    /**
     * @var string Stores the virtual path for a request to MODx if the
     * friendly_alias_paths option is enabled.
     */
    var $virtualDir;
    /**
     * @var object An error_handler for the modX instance.
     */
    var $errorHandler= null;
    /**
     * @var array An array of regex patterns regulary cleansed from content.
     */
    var $sanitizePatterns = array(
        'scripts'   => '@<script[^>]*?>.*?</script>@si',
        'entities'  => '@&#(\d+);@e',
        'tags'      => '@\[\[(.*?)\]\]@si',
    );
    /**
     * @var integer An integer representing the session state of modX.
     */
    var $_sessionState= MODX_SESSION_STATE_UNINITIALIZED;
    /**
     * @var array A config array that stores the bootstrap settings.
     */
    var $_config= null;
    /**
     * @var array A config array that stores the system-wide settings.
     */
    var $_systemConfig= null;
    /**
     * @var array A config array that stores the user settings.
     */
    var $_userConfig= array();
    var $_logSequence= 0;

    /**
     * @var DBAPI An instance of the DBAPI helper class.
     * @deprecated Aug 28, 2006 For legacy component support only; use xPDO
     * methods inherited by modX class instead. To be removed in 2.1.
     */
    var $db= null;

    var $pluginCache= array ();

    /**#@+
     * @deprecated 2006-09-15 To be removed in 2.1
     */
    var $Event= null;
    var $documentMap= null;
    var $documentListing= null;
    var $documentIdentifier= null;
    var $documentMethod= null;
    var $documentContent= null;
    var $documentOutput= null;
    var $documentObject= null;
    var $documentGenerated= false;
    var $stopOnNotice= false;
    var $dumpSQL= false;
    /**#@-*/

    /**
     * Harden the environment against common security flaws.
     *
     * @static
     */
    function protect() {
        if (isset ($_SERVER['QUERY_STRING']) && strpos(urldecode($_SERVER['QUERY_STRING']), chr(0)) !== false) die();
        if (@ ini_get('register_globals') && isset ($_REQUEST)) {
            while (list($key, $value)= each($_REQUEST)) {
                $GLOBALS[$key] = null;
                unset ($GLOBALS[$key]);
            }
        }
        $targets= array ('PHP_SELF', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'QUERY_STRING');
        foreach ($targets as $target) {
            $_SERVER[$target] = isset ($_SERVER[$target]) ? htmlspecialchars($_SERVER[$target], ENT_QUOTES) : null;
        }
    }

    /**
     * Sanitize values of an array using regular expression patterns.
     *
     * @static
     * @param array $target The target array to sanitize.
     * @param array|string $patterns A regular expression pattern, or array of
     * regular expression patterns to apply to all values of the target.
     * @param integer $depth The maximum recursive depth to sanitize if the
     * target contains values that are arrays.
     * @return array The sanitized array.
     */
    function sanitize(& $target, $patterns= array(), $depth= 3) {
        while (list($key, $value)= each($target)) {
            if (is_array($value) && $depth > 0) {
                modX :: sanitize($value, $patterns, $depth--);
            } elseif (is_string($value)) {
                if (!empty($patterns))
                    $value= preg_replace($patterns, '', $value);
                if (get_magic_quotes_gpc()) {
                    $target[$key]= stripslashes($value);
                } else {
                    $target[$key]= $value;
                }
            }
        }
        return $target;
    }

    function modX($configPath= '', $options = array()) {
        $this->__construct($configPath, $options);
    }
    function __construct($configPath= '', $options = array()) {
        global $database_type, $database_server, $dbase, $database_user,
               $database_password, $database_connection_charset, $table_prefix;
        modX :: protect();
        if (empty ($configPath)) {
            $configPath= MODX_CORE_PATH . 'config/';
        }
        if (@ include ($configPath . MODX_CONFIG_KEY . '.inc.php')) {
            $cachePath= MODX_CORE_PATH . 'cache/';
            if (MODX_CONFIG_KEY !== 'config') $cachePath .= MODX_CONFIG_KEY . '/';
            $options = array_merge(
                array (
                    XPDO_OPT_CACHE_PATH => $cachePath,
                    XPDO_OPT_TABLE_PREFIX => $table_prefix,
                    XPDO_OPT_HYDRATE_FIELDS => true,
                    XPDO_OPT_HYDRATE_RELATED_OBJECTS => true,
                    XPDO_OPT_HYDRATE_ADHOC_FIELDS => true,
                    XPDO_OPT_LOADER_CLASSES => array('modAccessibleObject'),
                    XPDO_OPT_VALIDATOR_CLASS => 'validation.modValidator',
                    XPDO_OPT_VALIDATE_ON_SAVE => true,
                    'cache_system_settings' => true
                ),
                $options
            );
            parent :: __construct(
                $database_type . ':host=' . $database_server . ';dbname=' . trim($dbase,'`') . ';charset=' . $database_connection_charset,
                $database_user,
                $database_password,
                $options,
                array (
                    PDO_ATTR_ERRMODE => PDO_ERRMODE_SILENT,
                    PDO_ATTR_PERSISTENT => false,
                    PDO_MYSQL_ATTR_USE_BUFFERED_QUERY => true
                )
            );
            $this->setPackage('modx', MODX_CORE_PATH . 'model/');
            $this->setLogTarget($this->getOption('log_target', null, 'FILE'));
        } else {
            $this->sendError($this->getOption('error_type', null, 'unavailable'), $options);
        }
    }

    /**
     * Initializes the modX engine.
     *
     * This includes preparing the session, pre-loading some common
     * classes and objects, the current site and context settings, extension
     * packages used to override session handling, error handling, or other
     * initialization classes
     *
     * @param string Indicates the context to initialize.
     * @return void
     */
    function initialize($contextKey= 'web') {
        if (!$this->_initialized) {
            if (!$this->startTime) {
                $mtime= microtime();
                $mtime= explode(" ", $mtime);
                $mtime= $mtime[1] + $mtime[0];
                $this->startTime= $this->getMicroTime();
            }

            $this->loadClass('modAccess');
            $this->loadClass('modAccessibleObject');
            $this->loadClass('modAccessibleSimpleObject');
            $this->loadClass('modResource');
            $this->loadClass('modElement');
            $this->loadClass('modScript');
            $this->loadClass('modPrincipal');
            $this->loadClass('modUser');

            $this->getCacheManager();
            $this->getConfig();
            $this->_initContext($contextKey);

            if (isset($this->config['extension_packages']) && ($extPackages= explode(',', $this->config['extension_packages']))) {
                foreach ($extPackages as $extPackage) {
                    $exploded= explode(':', $extPackage);
                    if ($exploded && count($exploded) == 2) {
                        $this->addPackage($exploded[0], $exploded[1]);
                    }
                }
            }

            $this->_initSession();
            $this->_initErrorHandler();
            $this->_initCulture();

            $this->getService('registry', 'registry.modRegistry');

            if ($this->loadClass('DBAPI', '', false, true)) {
                $this->db= new DBAPI();
            }

            if (is_array ($this->config)) {
                $this->setPlaceholders($this->config, '+');
            }

            $this->_initialized= true;
        }
    }

    /**
     * Sets the debugging features of the modX instance.
     *
     * @param boolean|int $debug Boolean or bitwise integer describing the
     * debug state and/or PHP error reporting level.
     * @param boolean $stopOnNotice Indicates if processing should stop when
     * encountering PHP errors of type E_NOTICE.
     * @return boolean|int The previous value.
     */
    function setDebug($debug= true, $stopOnNotice= false) {
        $oldValue= $this->getDebug();
        if ($debug === true) {
            error_reporting(E_ALL);
            parent :: setDebug(true);
        } elseif ($debug === false) {
            error_reporting(0);
            parent :: setDebug(false);
        } else {
            error_reporting(intval($debug));
            parent :: setDebug(intval($debug));
        }
        $this->stopOnNotice= $stopOnNotice;
        return $oldValue;
    }

    /**
     * Get an extended xPDOCacheManager instance responsible for MODx caching.
     *
     * @return object A modCacheManager registered for this modX instance.
     */
    function getCacheManager() {
        if ($this->cacheManager === null) {
            if ($this->loadClass('cache.xPDOCacheManager', XPDO_CORE_PATH, true, true)) {
                $cacheManagerClass= $this->getOption('modCacheManager.class', array(), 'modCacheManager');
                if ($className= $this->loadClass($cacheManagerClass, '', false, true)) {
                    if ($this->cacheManager= new $className ($this)) {
                        $this->_cacheEnabled= true;
                    }
                }
            }
        }
        return $this->cacheManager;
    }

    /**
     * Load and return a named service class instance.
     *
     * @param string $name The variable name of the instance.
     * @param string $class The service class name.
     * @param string $path An optional root path to search for the class.
     * @param array $params An array of optional params to pass to the service
     * class constructor.
     * @return object The service class instance or null if it could not be loaded.
     */
    function getService($name, $class= '', $path= '', $params= array ()) {
        $service= null;
        if (!isset ($this->services[$name]) || !is_object($this->services[$name])) {
            if (empty ($class) && isset ($this->config[$name . '.class'])) {
                $class= $this->config[$name . '.class'];
            } elseif (empty ($class)) {
                $class= $name;
            }
            if ($className= $this->loadClass($class, $path, false, true)) {
                if ($service= & new $className ($this, $params)) {
                    $this->services[$name]= $service;
                    $this->$name= & $this->services[$name];
                }
            }
        }
        if (array_key_exists($name, $this->services)) {
            $service= & $this->services[$name];
        } else {
            if ($this->getDebug() === true) {
                $this->log(MODX_LOG_LEVEL_DEBUG, "Problem getting service {$name}, instance of class {$class}, from path {$path}, with params " . print_r($params, true));
            } else {
                $this->log(MODX_LOG_LEVEL_ERROR, "Problem getting service {$name}, instance of class {$class}, from path {$path}");
            }
        }
        return $service;
    }

    /**
     * Gets the MODx parser.
     *
     * Returns an instance of modParser responsible for parsing tags in element
     * content, performing actions, returning content and/or sending other responses
     * in the process.
     *
     * @return object The modParser for this modX instance.
     */
    function getParser() {
        return $this->getService('parser', 'modParser');
    }

    /**
     * Gets all of the parent resource ids for a given resource.
     *
     * @param integer $id The resource id for the starting node.
     * @param integer $height How many levels max to search for parents (default 10).
     * @return array An array of all the parent resource ids for the specified resource.
     */
    function getParentIds($id= null, $height= 10) {
        $parentId= 0;
        $parents= array ();
        if ($id && $height >= 0) {
            foreach ($this->resourceMap as $parentId => $mapNode) {
                if (array_search($id, $mapNode) !== false) {
                    $parents[]= $parentId;
                    break;
                }
            }
            if ($parentId && !empty($parents)) {
                $parents= array_merge($parents, $this->getParentIds($parentId, $height--));
            }
        }
        return $parents;
    }

    /**
     * Gets all of the child resource ids for a given resource.
     *
     * @param integer $id The resource id for the starting node.
     * @param integer $depth How many levels max to search for children (default 10).
     * @return array An array of all the child resource ids for the specified resource.
     */
    function getChildIds($id= null, $depth= 10) {
        $children= array ();
        if ($id !== null && intval($depth) >= 1) {
            $id= intval($id);
            if (isset ($this->resourceMap["{$id}"])) {
                if ($children= $this->resourceMap["{$id}"]) {
                    foreach ($children as $child) {
                    	$processDepth = $depth - 1;
                        if ($c= $this->getChildIds($child, $processDepth)) {
                            $children= array_merge($children, $c);
                        }
                    }
                }
            }
        }
        return $children;
    }

    /**
     * Get a site tree from a single or multiple modResource instances.
     *
     * @param int|array $id A single or multiple modResource ids to build the
     * tree from.
     * @param int $depth The maximum depth to build the tree (default 10).
     * @return array An array containing the tree structure.
     */
    function getTree($id= null, $depth= 10) {
        $tree= array ();
        if ($id !== null) {
            if (is_array ($id)) {
                foreach ($id as $k => $v) {
                    $tree[$v]= $this->getTree($v, $depth);
                }
            }
            elseif ($branch= $this->getChildIds($id, 1)) {
                foreach ($branch as $key => $child) {
                    if ($depth > 0 && $leaf= $this->getTree($child, $depth--)) {
                        $tree[$child]= $leaf;
                    } else {
                        $tree[$child]= $child;
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * Sets a placeholder value.
     *
     * @param string $key The unique string key which identifies the
     * placeholder.
     * @param mixed $value The value to set the placeholder to.
     */
    function setPlaceholder($key, $value) {
        if (is_string($key)) {
            $this->placeholders["{$key}"]= $value;
        }
    }

    /**
     * Sets a collection of placeholders stored in an array or as object vars.
     *
     * An optional namespace parameter can be prepended to each placeholder key in the collection,
     * to isolate the collection of placeholders.
     *
     * Note that unlike toPlaceholders(), this function does not add separators between the
     * namespace and the placeholder key. Use toPlaceholders() when working with multi-dimensional
     * arrays or objects with variables other than scalars so each level gets delimited by a
     * separator.
     *
     * @param array|object $placeholders An array of values or object to set as placeholders.
     * @param string $namespace A namespace prefix to prepend to each placeholder key.
     */
    function setPlaceholders($placeholders, $namespace= '') {
        $this->toPlaceholders($placeholders, $namespace, '');
    }

    /**
     * Sets placeholders from values stored in arrays and objects.
     *
     * Each recursive level adds to the prefix, building an access path using an optional separator.
     *
     * @param array|object $subject An array or object to process.
     * @param string $prefix An optional prefix to be prepended to the placeholder keys. Recursive
     * calls prepend the parent keys.
     * @param string $separator A separator to place in between the prefixes and keys. Default is a
     * dot or period: '.'.
     */
    function toPlaceholders($subject, $prefix= '', $separator= '.') {
        if (is_object($subject)) {
            if (is_a($subject, 'xPDOObject')) {
                $subject= $subject->toArray();
            } else {
                $subject= get_object_vars($subject);
            }
        }
        if (is_array($subject)) {
            foreach ($subject as $key => $value) {
                $this->toPlaceholder($key, $value, $prefix, $separator);
            }
        }
    }

    /**
     * Recursively validates and sets placeholders appropriate to the value type passed.
     *
     * @param string $key The key identifying the value.
     * @param mixed $value The value to set.
     * @param string $prefix A string prefix to prepend to the key. Recursive calls prepend the
     * parent keys as well.
     * @param string $separator A separator placed in between the prefix and the key. Default is a
     * dot or period: '.'.
     */
    function toPlaceholder($key, $value, $prefix= '', $separator= '.') {
        if (!empty($prefix) && !empty($separator)) {
            $prefix .= $separator;
        }
        if (is_array($value) || is_object($value)) {
            $this->toPlaceholders($value, "{$prefix}{$key}");
        } elseif (is_scalar($value)) {
            $this->setPlaceholder("{$prefix}{$key}", $value);
        }
    }

    /**
     * Get a placeholder value by key.
     *
     * @param string $key The key of the placeholder to a return a value from.
     * @return mixed The value of the requested placeholder, or an empty string if not located.
     */
    function getPlaceholder($key) {
        $placeholder= null;
        if (is_string($key) && array_key_exists($key, $this->placeholders)) {
            $placeholder= & $this->placeholders["{$key}"];
        }
        return $placeholder;
    }

    /**
     * Unset a placeholder value by key.
     *
     * @param string $key The key of the placeholder to unset.
     */
    function unsetPlaceholder($key) {
        if (is_string($key) && array_key_exists($key, $this->placeholders)) {
            unset($this->placeholders[$key]);
        }
    }

    /**
     * Unset multiple placeholders, either by prefix or an array of keys.
     *
     * @param string|array $keys A string prefix or an array of keys indicating
     * the placeholders to unset.
     */
    function unsetPlaceholders($keys) {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                if (is_string($key)) $this->unsetPlaceholder($key);
                if (is_array($key)) $this->unsetPlaceholders($key);
            }
        } elseif (is_string($keys)) {
            $placeholderKeys = array_keys($this->placeholders);
            foreach ($placeholderKeys as $key) {
                if (strpos($key, $keys) === 0) $this->unsetPlaceholder($key);
            }
        }
    }

    /**
     * Generates a URL representing a specified resource.
     *
     * @param integer $id The id of a resource.
     * @param string $context Specifies a context to limit URL generation to.
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
    function makeUrl($id, $context= '', $args= '', $scheme= -1) {
        $url= '';
        if ($validid = intval($id)) {
            $id = $validid;
            if ($context == '' || $this->context->get('key') == $context) {
                $url= $this->context->makeUrl($id, $args, $scheme);
            }
            if (empty($url) && ($context !== $this->context->get('key'))) {
                $ctx= null;
                if ($context == '') {
                    if ($results = $this->query("SELECT `context_key` FROM " . $this->getTableName('modResource') . " WHERE `id` = {$id}")) {
                        $contexts= $results->fetchAll(PDO_FETCH_COLUMN);
                        if ($contextKey = reset($contexts)) {
                            $ctx = $this->getContext($contextKey);
                        }
                    }
                } else {
                    $ctx = $this->getContext($context);
                }
                if ($ctx) {
                    $url= $ctx->makeUrl($id, $args, $scheme);
                }
            }

            if (!empty($url) && $this->getOption('xhtml_urls', array(), '0')) {
                $url= preg_replace("/&(?!amp;)/","&amp;", $url);
            }
        } else {
            $this->log(MODX_LOG_LEVEL_ERROR, '`' . $id . '` is not a valid integer and may not be passed to makeUrl()');
        }
        return $url;
    }

    /**
     * Send the user to a type-specific core error page and halt PHP execution.
     *
     * @param string $type The type of error to present.
     * @param array $options An array of options to provide for the error file.
     */
    function sendError($type = '', $options = array()) {
        if (!is_string($type) || empty($type)) $type = $this->getOption('error_type', $options, 'unavailable');
        while (@ob_end_clean()) {}
        if (file_exists(MODX_CORE_PATH . "error/{$type}.include.php")) {
            @include(MODX_CORE_PATH . "error/{$type}.include.php");
        }
        header($this->getOption('error_header', $options, 'HTTP/1.1 503 Service Unavailable'));
        $errorPageTitle = $this->getOption('error_pagetitle', $options, 'Error 503: Site temporarily unavailable');
        $errorMessage = $this->getOption('error_message', $options, '<h1>' . $this->getOption('site_name', $options, 'Error 503') . '</h1><p>Site temporarily unavailable.</p>');
        echo "<html><head><title>{$errorPageTitle}</title></head><body>{$errorMessage}</body></html>";
        exit();
    }

    /**
     * Sends a redirect to the specified URL using the specified method.
     *
     * Valid $type values include:
     *    REDIRECT_REFRESH  Uses the header refresh method
     *    REDIRECT_META  Sends a a META HTTP-EQUIV="Refresh" tag to the output
     *    REDIRECT_HEADER  Uses the header location method
     *
     * REDIRECT_HEADER is the default.
     *
     * @param string $url The URL to redirect the client browser to.
     * @param integer $count_attempts The number of times to attempt redirection.
     * @param string $type The type of redirection to attempt.
     */
    function sendRedirect($url, $count_attempts= 0, $type= '') {
        if (!$this->getResponse()) {
            $this->log(MODX_LOG_LEVEL_FATAL, "Could not load response class.");
        }
        $this->response->sendRedirect($url, $count_attempts, $type);
    }

    /**
     * Forwards the request to another resource without changing the URL.
     *
     * @param integer $id The resource identifier.
     * @param string $options An array of options for the process.
     */
    function sendForward($id, $options = null) {
        if (!$this->getRequest()) {
            $this->log(MODX_LOG_LEVEL_FATAL, "Could not load request class.");
        }
        $idInt = intval($id);
        if (is_string($options) && !empty($options)) {
            $options = array('response_code' => $options);
        } elseif (!is_array($options)) {
            $options = array();
        }
        if ($idInt > 0) {
            $this->resource= $this->request->getResource('id', $idInt);
            if ($this->resource) {
                $this->resourceIdentifier= $idInt;
                $this->resourceMethod= 'id';
                if (isset($options['response_code']) && !empty($options['response_code'])) {
                    header($options['response_code']);
                }
                $this->request->prepareResponse();
                exit();
            }
            $options= array_merge(
                array(
                    'error_type' => '404'
                    ,'error_header' => $this->getOption('error_page_header', null, 'HTTP/1.1 404 Not Found')
                    ,'error_pagetitle' => $this->getOption('error_page_pagetitle', null, 'Error 404: Page not found')
                    ,'error_message' => $this->getOption('error_page_message', null, '<h1>Page not found</h1><p>The page you requested was not found.</p>')
                ),
                $options
            );
        }
        $this->sendError($id, $options);
    }

    /**
     * Send the user to a MODx virtual error page.
     *
     * @uses invokeEvent() The OnPageNotFound event is invoked before the error page is forwarded
     * to.
     * @param array $options An array of options to provide for the OnPageNotFound event and error
     * page.
     */
    function sendErrorPage($options = null) {
        if (!is_array($options)) $options = array();
        $options= array_merge(
            array(
                'response_code' => $this->getOption('error_page_header', $options, 'HTTP/1.1 404 Not Found')
                ,'error_type' => '404'
                ,'error_header' => $this->getOption('error_page_header', null, 'HTTP/1.1 404 Not Found')
                ,'error_pagetitle' => $this->getOption('error_page_pagetitle', null, 'Error 404: Page not found')
                ,'error_message' => $this->getOption('error_page_message', null, '<h1>Page not found</h1><p>The page you requested was not found.</p>')
            ),
            $options
        );
        $this->invokeEvent('OnPageNotFound', $options);
        $this->sendForward($this->getOption('error_page', $options, '404'), $options);
    }

    /**
     * Send the user to the MODx unauthorized page.
     *
     * @uses invokeEvent() The OnPageNotFound event is invoked before the unauthorized page is
     * forwarded to.
     * @param array $options An array of options to provide for the OnPageUnauthorized
     * event and unauthorized page.
     */
    function sendUnauthorizedPage($options = null) {
        if (!is_array($options)) $options = array();
        $options= array_merge(
            array(
                'response_code' => $this->getOption('unauthorized_page_header' ,$options ,'HTTP/1.1 401 Unauthorized')
                ,'error_type' => '401'
                ,'error_header' => $this->getOption('unauthorized_page_header', null, 'HTTP/1.1 401 Unauthorized')
                ,'error_pagetitle' => $this->getOption('unauthorized_page_pagetitle', null, 'Error 401: Unauthorized')
                ,'error_message' => $this->getOption('unauthorized_page_message', null, '<h1>Unauthorized</h1><p>You are not authorized to view the requested content.</p>')
            ),
            $options
        );
        $this->invokeEvent('OnPageUnauthorized', $options);
        $this->sendForward($this->getOption('unauthorized_page', $options, '401'), $options);
    }

    /**
     * Get the current authenticated User and assigns it to the modX instance.
     *
     * @param string $contextKey An optional context to get the user from.
     * @return modUser The user object authenticated for the request.
     */
    function getUser($contextKey= '') {
        if ($contextKey == '') {
            if ($this->context !== null) {
                $contextKey= $this->context->get('key');
            }
        }
        if ($this->user === null || !is_object($this->user)) {
            $this->user= $this->getAuthenticatedUser($contextKey);
            if ($contextKey !== 'mgr' && !$this->user) {
                $this->user= $this->getAuthenticatedUser('mgr');
            }
        }
        if ($this->user !== null && is_object($this->user)) {
            if ($this->user->hasSessionContext($contextKey)) {
                if (isset ($_SESSION["modx.{$contextKey}.user.config"])) {
                    $this->_userConfig= $_SESSION["modx.{$contextKey}.user.config"];
                } else {
                    $settings= $this->user->getMany('modUserSetting');
                    if (is_array($settings) && !empty ($settings)) {
                        foreach ($settings as $setting) {
                            $v= $setting->get('value');
                            $matches= array();
                            if (preg_match_all('~\{(.*?)\}~', $v, $matches, PREG_SET_ORDER)) {
                                $matchValue= '';
                                foreach ($matches as $match) {
                                    if (isset ($this->config["{$match[1]}"])) {
                                        $matchValue= $this->config["{$match[1]}"];
                                    } else {
                                        $matchValue= '';
                                    }
                                    $v= str_replace($match[0], $matchValue, $v);
                                }
                            }
                            $this->_userConfig[$setting->get('key')]= $v;
                        }
                    }
                }
                if (is_array ($this->_userConfig) && !empty ($this->_userConfig)) {
                    $_SESSION["modx.{$contextKey}.user.config"]= $this->_userConfig;
                    $this->config= array_merge($this->config, $this->_userConfig);
                }
            }
        } else {
            $this->user = $this->newObject('modUser', array(
                    'id' => 0,
                    'username' => '(anonymous)'
                )
            );
        }
        return $this->user;
    }

    /**
     * Gets the user authenticated in the specified context.
     *
     * @param string $contextKey Optional context key; uses current context
     * by default.
     * @return unknown
     */
    function getAuthenticatedUser($contextKey= '') {
        $user= null;
        if ($contextKey == '') {
            if ($this->context !== null) {
                $contextKey= $this->context->get('key');
            }
        }
        if ($contextKey && isset ($_SESSION['modx.user.contextTokens'][$contextKey])) {
            $user= $this->getObject('modUser', intval($_SESSION['modx.user.contextTokens'][$contextKey]), true);
            if ($user) {
                $user->getSessionContexts();
            }
        }
        return $user;
    }

    /**
     * Checks to see if the user has a session in the specified context.
     *
     * @param string $sessionContext The context to test for a session key in.
     * @return boolean True if the user is valid in the context specified.
     */
    function checkSession($sessionContext= 'web') {
        $hasSession = false;
        if ($this->user !== null) {
            $hasSession = $this->user->hasSessionContext($sessionContext);
        }
        return $hasSession;
    }

    /**
     * Gets the modX core version data.
     *
     * @return array The version data loaded from the config version file.
     */
    function getVersionData() {
        if ($this->version === null) {
            $this->version= @ include_once MODX_CORE_PATH . "docs/version.inc.php";
        }
        return $this->version;
    }

    /**
     * Reload the config settings.
     *
     * @return array An associative array of configuration key/values
     */
    function reloadConfig() {
        $cacheManager= $this->getCacheManager();
        $cacheManager->clearCache();

        if (!$this->_loadConfig()) {
            $this->log(MODX_LOG_LEVEL_ERROR, "Could not reload core MODx configuration!");
        }
        return $this->config;
    }

    /**
     * Get the configuration for the site.
     *
     * @return array An associate array of configuration key/values
     */
    function getConfig() {
        if (!$this->_initialized || !is_array($this->config) || empty ($this->config)) {
            if (!isset ($this->config['base_url']))
                $this->config['base_url']= MODX_BASE_URL;
            if (!isset ($this->config['base_path']))
                $this->config['base_path']= MODX_BASE_PATH;
            if (!isset ($this->config['core_path']))
                $this->config['core_path']= MODX_CORE_PATH;
            if (!isset ($this->config['url_scheme']))
                $this->config['url_scheme']= MODX_URL_SCHEME;
            if (!isset ($this->config['http_host']))
                $this->config['http_host']= MODX_HTTP_HOST;
            if (!isset ($this->config['site_url']))
                $this->config['site_url']= MODX_SITE_URL;
            if (!isset ($this->config['manager_path']))
                $this->config['manager_path']= MODX_MANAGER_PATH;
            if (!isset ($this->config['manager_url']))
                $this->config['manager_url']= MODX_MANAGER_URL;
            if (!isset ($this->config['assets_path']))
                $this->config['assets_path']= MODX_ASSETS_PATH;
            if (!isset ($this->config['assets_url']))
                $this->config['assets_url']= MODX_ASSETS_URL;
            if (!isset ($this->config['connectors_path']))
                $this->config['connectors_path']= MODX_CONNECTORS_PATH;
            if (!isset ($this->config['connectors_url']))
                $this->config['connectors_url']= MODX_CONNECTORS_URL;
            if (!isset ($this->config['processors_path']))
                $this->config['processors_path']= MODX_PROCESSORS_PATH;
            if (!isset ($this->config['request_param_id']))
                $this->config['request_param_id']= 'id';
            if (!isset ($this->config['request_param_alias']))
                $this->config['request_param_alias']= 'q';
            if (!isset ($this->config['https_port']))
                $this->config['https_port']= isset($GLOBALS['https_port']) ? $GLOBALS['https_port'] : 443;
            if (!isset ($this->config['error_handler_class']))
                $this->config['error_handler_class']= 'error.modErrorHandler';

            $this->_config= $this->config;
            if (!$this->_loadConfig()) {
                $this->log(MODX_LOG_LEVEL_FATAL, "Could not load core MODx configuration!");
                return null;
            }
        }
        return $this->config;
    }

    /**
     * Alias for getConfig().
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getSettings() {
        $this->getConfig();
        return $this->config;
    }

    /**
     * Initialize, cleanse, and process a request made to a modX site.
     *
     * @return mixed The result of the request handler.
     */
    function handleRequest() {
        if ($this->getRequest()) {
            return $this->request->handleRequest();
        }
    }

    /**
     * Attempt to load the request handler class, if not already loaded.
     *
     * @access public
     * @param $string The class name of the response class to load. Defaults to
     * modResponse; is ignored if the Setting "modResponse.class" is set.
     * @param $path The absolute path by which to load the response class from.
     * Defaults to the current MODx model path.
     * @return boolean Returns true if a valid request handler object was
     * loaded on this or any previous call to the function, false otherwise.
     */
    function getRequest($class= 'modRequest', $path= '') {
        if ($this->request === null || !is_a($this->request, 'modRequest')) {
            $requestClass = $this->getOption('modRequest.class',$this->config,$class);
            if ($className= $this->loadClass($requestClass, $path, !empty($path), true))
                $this->request= new $className ($this);
        }
        return is_object($this->request) && is_a($this->request, 'modRequest');
    }

    /**
     * Attempt to load the response handler class, if not already loaded.
     *
     * @access public
     * @param $string The class name of the response class to load. Defaults to
     * modResponse; is ignored if the Setting "modResponse.class" is set.
     * @param $path The absolute path by which to load the response class from.
     * Defaults to the current MODx model path.
     * @return boolean Returns true if a valid response handler object was
     * loaded on this or any previous call to the function, false otherwise.
     */
    function getResponse($class= 'modResponse', $path= '') {
        $responseClass= $this->getOption('modResponse.class',$this->config,$class);
        $className= $this->loadClass($responseClass, $path, !empty($path), true);
        if ($this->response === null || !is_a($this->response, $className)) {
            if ($className) $this->response= new $className ($this);
        }
        return is_a($this->response, $className);
    }

    /**
     * Register CSS to be injected inside the HEAD tag of a resource.
     *
     * @param string $src The CSS to be injected before the closing HEAD tag in
     * an HTML response.
     */
    function regClientCSS($src) {
        if (isset ($this->loadedjscripts[$src]) && $this->loadedjscripts[$src]) {
            return '';
        }
        $this->loadedjscripts[$src]= true;
        if (strpos(strtolower($src), "<style") !== false || strpos(strtolower($src), "<link") !== false) {
            $this->sjscripts[count($this->sjscripts)]= $src;
        } else {
            $this->sjscripts[count($this->sjscripts)]= '<link rel="stylesheet" href="' . $src . '" type="text/css" />';
        }
    }

    /**
     * Register JavaScript to be injected inside the HEAD tag of a resource.
     *
     * @param string $src The JavaScript to be injected before the closing HEAD
     * tag of an HTML response.
     * @param boolean $plaintext Optional param to treat the $src as plaintext
     * rather than assuming it is JavaScript.
     */
    function regClientStartupScript($src, $plaintext= false) {
        if (!empty ($src) && !array_key_exists($src, $this->loadedjscripts)) {
            if (isset ($this->loadedjscripts[$src]))
                return '';
            $this->loadedjscripts[$src]= true;
            if ($plaintext == true) {
                $this->sjscripts[count($this->sjscripts)]= $src;
            } elseif (strpos(strtolower($src), "<script") !== false) {
                $this->sjscripts[count($this->sjscripts)]= $src;
            } else {
                $this->sjscripts[count($this->sjscripts)]= '<script type="text/javascript" src="' . $src . '"></script>';
            }
        }
    }

    /**
     * Register JavaScript to be injected before the closing BODY tag.
     *
     * @param string $src The JavaScript to be injected before the closing BODY
     * tag in an HTML response.
     * @param boolean $plaintext Optional param to treat the $src as plaintext
     * rather than assuming it is JavaScript.
     */
    function regClientScript($src, $plaintext= false) {
        if (isset ($this->loadedjscripts[$src]))
            return '';
        $this->loadedjscripts[$src]= true;
        if ($plaintext == true) {
            $this->jscripts[count($this->jscripts)]= $src;
        } elseif (strpos(strtolower($src), "<script") !== false) {
            $this->jscripts[count($this->jscripts)]= $src;
        } else {
            $this->jscripts[count($this->jscripts)]= '<script type="text/javascript" src="' . $src . '"></script>';
        }
    }

    /**
     * Register HTML to be injected before the closing HEAD tag.
     *
     * @param string $html The HTML to be injected.
     */
    function regClientStartupHTMLBlock($html) {
        return $this->regClientStartupScript($html, true);
    }

    /**
     * Register HTML to be injected before the closing BODY tag.
     *
     * @param string $html The HTML to be injected.
     */
    function regClientHTMLBlock($html) {
        return $this->regClientScript($html, true);
    }

    /**
     * Returns all registered JavaScript and HTML blocks.
     *
     * @access public
     * @return string The parsed HTML of the client scripts.
     */
    function getRegisteredClientScripts() {
        $string= '';
        if (is_array($this->jscripts)) {
            $string= implode("\n",$this->jscripts);
        }
        return $string;
    }

    /**
     * Returns all registered startup CSS, JavaScript, or HTML blocks.
     *
     * @access public
     * @return string The parsed HTML of the startup scripts.
     */
    function getRegisteredClientStartupScripts() {
        $string= '';
        if (is_array ($this->sjscripts)) {
            $string= implode("\n", $this->sjscripts);
        }
        return $string;
    }

    /**
     * Legacy call to set the current documentObject being handled by MODx.
     *
     * @param string $method 'id' or 'alias' to indicate the lookup method.
     * @param string|int $identifier The identifier for looking up the document.
     * @return array An associative array containing all the document data.
     * @deprecated 0.9.7 - Jan 18, 2007
     */
    function getDocumentObject($method, $identifier) {
        if (!$this->getRequest()) {
            $this->log(MODX_LOG_LEVEL_FATAL, 'Could not load request class.');
        }
        $this->resource= $this->request->getResource($method, $identifier);
        $documentObject= & $this->documentObject;
        return $documentObject;
    }

    /**
     * Invokes a specified Event with an optional array of parameters.
     *
     * @access public
     * @param string $eventName Name of an event to invoke.
     * @param array $params Optional params provided to the elements registered with an event.
     * @todo refactor this completely, yuck!!
     */
    function invokeEvent($eventName, $params= array ()) {
        if (!$eventName)
            return false;
        if ($this->eventMap === null)
            $this->_initEventMap($this->context->get('key'));
        if (!isset ($this->eventMap[$eventName])) {
            $this->log(MODX_LOG_LEVEL_DEBUG,'System event '.$eventName.' was executed but does not exist.');
            return false;
        }
        $results= array ();
        if (count($this->eventMap[$eventName])) {
            $this->event= new modSystemEvent();
            foreach ($this->eventMap[$eventName] as $pluginId => $pluginPropset) {
                $plugin= null;
                $this->Event= & $this->event;
                $this->event->_resetEventObject();
                $this->event->name= $eventName;
                if (isset ($this->pluginCache[$pluginId])) {
                    $plugin= $this->newObject('modPlugin');
                    $plugin->fromArray($this->pluginCache[$pluginId], '', true, true);
                    $plugin->_processed = false;
                    if ($plugin->get('disabled')) {
                        $plugin= null;
                    }
                } else {
                    $plugin= $this->getObject('modPlugin', array ('id' => intval($pluginId), 'disabled' => '0'), true);
                }
                if ($plugin) {
                    $this->event->activated= true;
                    $this->event->activePlugin= $plugin->get('name');
                    $this->event->propertySet= (($pspos = strpos($pluginPropset, ':')) > 1) ? substr($pluginPropset, $pspos + 1) : '';
                    $msg= $plugin->process($params);
                    $results[]= $this->event->_output;
                    if ($msg && is_string($msg)) {
                        $this->log(MODX_LOG_LEVEL_ERROR, '[' . $this->event->name . ']' . $msg);
                    } elseif ($msg === false) {
                        $this->log(MODX_LOG_LEVEL_ERROR, '[' . $this->event->name . '] Plugin failed!');
                    }
                    $this->event->activePlugin= '';
                    $this->event->propertySet= '';
                    if ($this->event->_propagate != true) {
                        break;
                    }
                }
            }
        }
        return $results;
    }

    /**
     * Executes a specific processor. The only argument is an array, which can
     * take the following values:
     *
     * - action - The action to take, similar to connector handling.
     * - processors_path - If specified, will override the default MODx
     * processors path.
     * - location - A prefix to load processor files from, will prepend to the
     * action parameter.
     *
     * @param array $options An array of options.
     * @return mixed $result The result of the processor.
     */
    function executeProcessor($options) {
        $result = null;
        if (is_array($options) && !empty($options) && isset($options['action']) && $this->getRequest()) {
            $processor = isset($options['processors_path']) ? $options['processors_path'] : $this->config['processors_path'];
            if (isset($options['location']) && !empty($options['location'])) $processor .= $options['location'] . '/';
            $processor .= str_replace('../', '', $options['action']) . '.php';
            if (file_exists($processor)) {
                if (!isset($this->lexicon)) $this->getService('lexicon', 'modLexicon');
                if (!isset($this->error)) $this->request->loadErrorHandler();
                $modx =& $this;
                $result = include $processor;
            }
        }
        return $result;
    }

    /**
     * Returns the current user ID, for the current or specified context.
     *
     * @param string $context The key of a valid modContext so you can retrieve
     * the current user ID from a different context than the current.
     * @return string The ID of the current user.
     */
    function getLoginUserID($context= '') {
        if ($context && isset ($_SESSION[$context . 'Validated'])) {
            return $_SESSION[$context . 'InternalKey'];
        }
        elseif (!$context && $this->isFrontend() && isset ($_SESSION['webValidated'])) {
            return $_SESSION['webInternalKey'];
        }
        elseif (!$context && $this->isBackend() && isset ($_SESSION['mgrValidated'])) {
            return $_SESSION['mgrInternalKey'];
        }
    }

    /**
     * Returns the current user name, for the current or specified context.
     *
     * @param string $context The key of a valid modContext so you can retrieve
     * the username from a different context than the current.
     * @return string The username of the current user.
     */
    function getLoginUserName($context= '') {
        if ($context && isset ($_SESSION[$context . 'Validated'])) {
            return $_SESSION[$context . 'Shortname'];
        }
        if ($this->isFrontend() && isset ($_SESSION['webValidated'])) {
            return $_SESSION['webShortname'];
        }
        elseif ($this->isBackend() && isset ($_SESSION['mgrValidated'])) {
            return $_SESSION['mgrShortname'];
        }
    }

    /**
     * Returns current login user type - web or manager.
     *
     * @deprecated 2007-09-17 To be removed in 1.0.
     * @return string 'web', 'manager' or an empty string.
     */
    function getLoginUserType() {
        if ($this->isFrontend() && isset ($_SESSION['webValidated'])) {
            return 'web';
        }
        elseif ($this->isBackend() && isset ($_SESSION['mgrValidated'])) {
            return 'manager';
        } else {
            return '';
        }
    }

    /**
     * Determines if the current webuser is a member of the specified webgroups.
     *
     * @param array $groupNames An array of groups to check against.
     * @return boolean True if the user is a member of any one of the supplied
     * group names, false otherwise.
     */
    function isMemberOfWebGroup($groupNames= array ()) {
        if (!is_array($groupNames))
            return false;
        // check cache
        $grpNames= isset ($_SESSION['webUserGroupNames']) ? $_SESSION['webUserGroupNames'] : false;
        if (!is_array($grpNames)) {
            if ($user= $this->getUser('web')) {
                if ($groupMemberships= $user->getMany('modWebGroupMember')) {
                    foreach ($groupMemberships as $gm) {
                        if ($group= $gm->getOne('modWebGroup')) {
                            $grpNames[]= $group->get('name');
                        }
                    }
                }
            }
            // save to session
            if ($grpNames) $_SESSION['webUserGroupNames']= $grpNames;
        }
        if ($grpNames && !empty ($grpNames)) {
            foreach ($groupNames as $k => $v) {
                if (in_array(trim($v), $grpNames)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get persistent data from a specific document.
     * @deprecated 2007-09-17 To be removed in 2.1.
     */
    function getDocument($id=0,$fields="*",$published=1, $deleted=0) {
        if($id==0) {
            return false;
        } else {
            $tmpArr[] = $id;
            $docs = $this->getDocuments($tmpArr, $published, $deleted, $fields,"","","",1);
            if($docs!=false) {
                return $docs[0];
            } else {
                return false;
            }
        }
    }

    /**
     * Get persistent data from a collection of specified documents.
     * @deprecated 2008-10-10 To be removed in 2.1.
     */
    function getDocuments($ids=array(), $published=1, $deleted=0, $fields="*", $where='', $sort="menuindex", $dir="ASC", $limit="") {
        $collection = array();
        $criteria = $this->newQuery('modResource');
        $criteria->setClassAlias('sc');
        $criteria->select($fields);
        $criteria->where('sc.id IN (' . implode(',', $ids) . ')', array(
            'sc.published' => $published,
            'sc.deleted' => $deleted
        ));
        if (!empty($where)) $criteria->andCondition($where);
        if (!empty($sort)) $criteria->sortby($sort, $dir);
        if (!empty($limit)) $criteria->limit($limit);
        if ($objCollection = $this->getCollection('modResource', $criteria)) {
            foreach ($objCollection as $obj) {
                array_push($collection, $obj->toArray());
            }
        }
        if (empty($collection)) $collection = false;
        return $collection;
    }

    /**
     * Legacy fatal error message.
     */
    function messageQuit($msg='unspecified error', $query='', $is_error=true, $nr='', $file='', $source='', $text='', $line='') {
        $this->log(MODX_LOG_LEVEL_FATAL, 'msg: ' . $msg . "\n" . 'query: ' . $query . "\n" . 'nr: ' . $nr . "\n" . 'file: ' . $file . "\n" . 'source: ' . $source . "\n" . 'text: ' . $text . "\n" . 'line: ' . $line . "\n");
    }


    /**
     * Process and return the output from a PHP snippet by name.
     *
     * @param string $snippetName The name of the snippet.
     * @param array $params An associative array of properties to pass to the
     * snippet.
     * @return string The processed output of the snippet.
     */
    function runSnippet($snippetName, $params= array ()) {
        $output= '';
        if ($snippet= $this->getObject('modSnippet', array ('name' => $snippetName), true)) {
            $snippet->setCacheable(false);
            $output= $snippet->process($params);
        }
        return $output;
    }

    /**
     * Process and return the output from an HTML chunk by name.
     *
     * @param string $chunkName The name of the chunk.
     * @param array $properties An associative array of properties to process
     * the chunk with.
     * @return string The processed output of the chunk.
     */
    function getChunk($chunkName, $properties= array ()) {
        $output= '';
        if ($chunk= $this->getObject('modChunk', array ('name' => $chunkName), true)) {
            $chunk->setCacheable(false);
            $output= $chunk->process($properties);
        }
        return $output;
    }

    /**
     * Parse a chunk using an associative array of replacement variables.
     *
     * @param string $chunkName The name of the chunk.
     * @param array $chunkArr An array of properties to replace in the chunk.
     * @param string $prefix The placeholder prefix, defaults to [[+.
     * @param string $suffix The placeholder suffix, defaults to ]].
     * @return string The processed chunk with the placeholders replaced.
     */
    function parseChunk($chunkName, $chunkArr, $prefix='[[+', $suffix=']]') {
        $chunk= '';
        if ($chunk= $this->getChunk($chunkName)) {
            if(is_array($chunkArr)) {
                reset($chunkArr);
                while (list($key, $value)= each($chunkArr)) {
                    $chunk= str_replace($prefix.$key.$suffix, $value, $chunk);
                }
            }
        }
        return $chunk;
    }

    /**
     * Strip unwanted HTML and PHP tags and supplied patterns from content.
     */
    function stripTags($html, $allowed= '', $patterns= array()) {
        $stripped= strip_tags($html, $allowed);
        if (is_array($patterns)) {
            if (empty($patterns)) {
                $patterns = $this->sanitizePatterns;
            }
            foreach ($patterns as $pattern) {
                $stripped= preg_replace($pattern, '', $stripped);
            }
        }
        return $stripped;
    }

    /**
     * Returns an array of resource groups that current user is assigned to.
     *
     * This function will first return the web user doc groups when running from
     * frontend otherwise it will return manager user's resource group.
     * @param boolean $resolveIds Set to true to return the resource group
     * names.
     * @return mixed An array of document group ids, names, false or empty string.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getUserDocGroups($resolveIds= false) {
        $dgn= false;
        if ($this->isFrontend() && isset ($_SESSION['webDocgroups']) && isset ($_SESSION['webValidated'])) {
            $dg= $_SESSION['webDocgroups'];
            $dgn= isset ($_SESSION['webDocgrpNames']) ? $_SESSION['webDocgrpNames'] : false;
        } elseif ($this->isBackend() && isset ($_SESSION['mgrDocgroups']) && isset ($_SESSION['mgrValidated'])) {
            $dg= $_SESSION['mgrDocgroups'];
            $dgn= $_SESSION['mgrDocgrpNames'];
        } else {
            $dg= '';
        }
        if (!$resolveIds) {
            return $dg;
        }
        elseif (is_array($dgn)) {
            return $dgn;
        }
        elseif (is_array($dg)) {
            $dgn= array ();
            $tbl= $this->getTableName('modResourceGroup');
            $criteria= new xPDOCriteria($this, "SELECT * FROM {$tbl} WHERE `id` IN (" . implode(",", $dg) . ")");
            $collResourceGroups= $this->getCollection('modResourceGroup', $criteria);
            foreach ($collResourceGroups as $rg) {
                $dgn[count($dgn)]= $rg->get('name');
            }
            if ($this->isFrontend()) {
                $_SESSION['webDocgrpNames']= $dgn;
            }
            else {
                $_SESSION['mgrDocgrpNames']= $dgn;
            }
        }
        return $dgn;
    }

    /**
     * Returns the full table name based on db settings.
     *
     * @deprecated Aug 28, 2006 Use {@link getTableName($className)} instead. To
     * be removed in 2.1.
     */
    function getFullTableName($tbl){
        return '`' . $this->db->config['dbase']. '`.`' .$this->db->config['table_prefix'].$tbl . '`';
    }

    /**
     * Get children of the specified resource without regard to status.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getAllChildren($id=0, $sort='menuindex', $dir='ASC', $fields='id, pagetitle, description, parent, alias, menutitle, class_key, context_key') {
        $collection = array();
        $criteria = $this->newQuery('modResource');
        $criteria->select($fields);
        $criteria->where(array('parent' => $id));
        if (!empty($sort)) $criteria->sortby($sort, $dir);
        if ($objCollection = $this->getCollection('modResource', $criteria)) {
            foreach ($objCollection as $obj) {
                array_push($collection, $obj->toArray());
            }
        }
        if (empty($collection)) $collection = false;
        return $collection;
    }

    /**
     * Get all published children documents of the specified document.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getActiveChildren($id=0, $sort='menuindex', $dir='ASC', $fields='id, pagetitle, description, parent, alias, menutitle, class_key, context_key') {
        $collection = array();
        $criteria = $this->newQuery('modResource');
        $criteria->setClassAlias('sc');
        $criteria->select($fields);
        $criteria->where(array(
            'sc.parent' => $id,
            'sc.published' => '1',
            'sc.deleted' => '0'
        ));
        if (!empty($sort)) $criteria->sortby($sort, $dir);
        if ($objCollection = $this->getCollection('modResource', $criteria)) {
            foreach ($objCollection as $obj) {
                array_push($collection, $obj->toArray());
            }
        }
        if (empty($collection)) $collection = false;
        return $collection;
    }

    /**
     * Get all children documents of the specified document.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getDocumentChildren($parentid= 0, $published= 1, $deleted= 0, $fields= "*", $where= '', $sort= "menuindex", $dir= "ASC", $limit= "") {
        $collection = array();
        $criteria = $this->newQuery('modResource');
        $criteria->setClassAlias('sc');
        $criteria->select($fields);
        $criteria->where(array(
            'sc.parent' => $parentid,
            'sc.published' => $published,
            'sc.deleted' => $deleted
        ));
        if (!empty($where)) $criteria->andCondition($where);
        if (!empty($sort)) $criteria->sortby($sort, $dir);
        if (!empty($limit)) $criteria->limit($limit);
        if ($objCollection = $this->getCollection('modResource', $criteria)) {
            foreach ($objCollection as $obj) {
                array_push($collection, $obj->toArray());
            }
        }
        if (empty($collection)) $collection = false;
        return $collection;
    }

    /**
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getDocumentChildrenTVars($parentid=0, $tvidnames=array(), $published=1, $docsort="menuindex", $docsortdir="ASC", $tvfields="*", $tvsort="rank", $tvsortdir="ASC") {
        $collection = array();
        $all= ($tvidnames=="*");
        $byName= (is_numeric($tvidnames[0]) == false);
        $criteria = $this->newQuery('modResource');
        $criteria->where(array(
            'sc.parent' => $parentid,
            'sc.published' => $published,
            'sc.deleted' => '0'
        ));
        if (!empty($sort)) $criteria->sortby($docsort, $docsortdir);
        if ($objCollection = $this->getCollection('modResource', $criteria)) {
            foreach ($objCollection as $obj) {
                $objArray= $obj->toArray();
                $tvs= $obj->getMany('modTemplateVar');
                foreach ($tvs as $tv) {
                    if (!$all) {
                        if ($byName) {
                            if (!in_array($tv->name, $tvidnames)) continue;
                        } else {
                            if (!in_array($tv->id, $tvidnames)) continue;
                        }
                    }
                    $objArray= array_merge($objArray, $tv->toArray());
                }
                array_push($collection, $objArray);
            }
        }
        if (empty($collection)) $collection = false;
        return $collection;
    }

    /**
     * Returns a single TV record.
     *
     * @param string $idname can be an id or name that belongs the template
     * that the current document is using.
     * @param string $fields
     * @param integer $docid
     * @param integer $published 1 for published, 0 for unpublished.
     * @return mixed An array of the template variable fields or false.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getTemplateVar($idname="", $fields = "*", $resourceId="", $published=1) {
        if($idname=="") {
            return false;
        }
        else {
            $result = $this->getTemplateVars(array($idname),$fields,$resourceId,$published,"","");
            return reset($result);
        }
    }

    /**
     * Returns an array of TV records.
     *
     * @param string|array $idnames Can be an id or name that belongs to
     * the template assigned to the current resource.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getTemplateVars($idnames=array(), $fields = "*", $resourceId="", $published=1, $sort="tv.rank,tvtpl.rank", $dir="ASC,ASC") {
        if(($idnames!='*' && !is_array($idnames)) || count($idnames)==0) {
            return false;
        }
        else {
            $resourceId = intval($resourceId);
            if ($resourceId < 1) {
                if (is_object($this->resource)) {
                    $resourceId = intval($this->resource->resourceIdentifier);
                }
            }
            $result = array();

            $fields = (empty($fields) || $fields=='*') ? "tv.*" : $this->getSelectColumns('modTemplateVar', 'tv', '', $fields);

            $query = $this->newQuery('modTemplateVar');
            $query->setClassAlias('tv');
            $query->innerJoin('modTemplateVarTemplate', 'tvtpl', array(
                'tvtpl.tmplvarid = tv.id',
            ));
            $query->innerJoin('modResource', 'sc', array(
                'sc.id = ' . intval($resourceId),
                'tvtpl.templateid = sc.template',
            ));
            $query->leftJoin('modTemplateVarResource', 'tvc', array(
                'tvc.tmplvarid = tv.id',
                array('tvc.contentid' => $resourceId)
            ));
            if ($idnames == '*') {
                $query->where(array('tv.id:!=' => '0'));
            } elseif (isset($idnames[0])) {
                if (intval($idnames[0])) {
                    $tvIdentifiers = implode(',', $idnames);
                    $query->where('tv.id IN (' . $tvIdentifiers . ')');
                } else {
                    $tvIdentifiers = implode("','", $idnames);
                    $query->where('tv.name IN ' . "('" . $tvIdentifiers . "')");
                }
            }
            if (!empty($sort)) {
                $sortby = explode(',', $sort);
                if (!empty($dir)) {
                    $sortdir = explode(',', $dir);
                } else {
                    $sortdir = array('');
                }
                if (is_array($sortby)) {
                    foreach ($sortby as $idx => $col) {
                        $sd = isset ($sortdir[$idx]) ? $sortdir[$idx] : '';
                        $query->sortby($col, $sd);
                    }
                }
            } else {
                $query->sortby('tvtpl.rank');
                $query->sortby('tv.rank');
            }
            $query->select('DISTINCT ' . $fields . ', IF(ISNULL(`tvc`.`value`),`tv`.`default_text`,`tvc`.`value`) AS `value`');

            $collection = $this->getCollection('modTemplateVar', $query);
            foreach ($collection as $pk => $tv) {
                $result[$tv->get('name')]= array (
                    'name' => $tv->get('name'),
                    'value' => $tv->getValue($resourceId),
                    'display' => $tv->get('display'),
                    'display_params' => $tv->get('display_params'),
                    'type' => $tv->get('type')
                );
            }
            return $result;
        }
    }

    /**
     * Gets an array of template variable data, with processed output.
     *
     * @param string|array $idnames
     * @param integer $resourceId
     * @param integer $published
     * @return mixed
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getTemplateVarOutput($idnames=array(), $resourceId="", $published=1) {
        if(count($idnames)==0) {
            return false;
        }
        else {
            $output = array();
            if ($resourceId=="") {
                $resourceId = $this->resourceIdentifier;
            }

            $query = $this->newQuery('modTemplateVar');
            $query->setClassAlias('tv');
            $query->innerJoin('modTemplateVarTemplate', 'tvtpl', array(
                'tvtpl.tmplvarid = tv.id',
            ));
            $query->innerJoin('modResource', 'sc', array(
                'sc.id = ' . intval($resourceId),
                'tvtpl.templateid = sc.template',
            ));
            $query->leftJoin('modTemplateVarResource', 'tvc', array(
                'tvc.tmplvarid = tv.id',
                'tvc.contentid = sc.id'
            ));
            if ($idnames == '*') {
                $query->where(array('tv.id:!=' => '0'));
            } elseif (isset($idnames[0])) {
                if (is_numeric($idnames[0])) {
                    $tvIdentifiers = implode(',', $idnames);
                    $query->where('tv.id IN (' . $tvIdentifiers . ')');
                } else {
                    $tvIdentifiers = implode("','", $idnames);
                    $query->where('tv.name IN (\'' . $tvIdentifiers . '\')');
                }
            }
            $query->sortby('tvtpl.rank');
            $query->sortby('tv.rank');
            $query->select('DISTINCT `tv`.*, IF(ISNULL(`tvc`.`value`),`tv`.`default_text`,`tvc`.`value`) AS `value`');

            $collection = $this->getCollection('modTemplateVar', $query);
            foreach ($collection as $pk => $tv) {
                $output[$tv->get('name')]= $tv->renderOutput($resourceId);
            }
            return $output;
        }
    }

    /**
     * Gets the parent resource.
     *
     * @param integer $pid
     * @param integer $active
     * @param string $fields
     * @return mixed
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getParent($pid=-1, $active=1, $fields='id, pagetitle, description, alias, parent, class_key, context_key') {
        if($pid==-1) {
            $pid = $this->documentObject['parent'];
            return ($pid==0)? false:$this->getPageInfo($pid,$active,$fields);
        }else if($pid==0) {
            return false;
        } else {
            $child = $this->getPageInfo($pid,$active,"id,parent");
            $pid = (isset ($child['parent']) && intval($child['parent']))? intval($child['parent']):0;
            return ($pid==0)? false:$this->getPageInfo($pid,$active,$fields);
        }
    }

    /**
     * Gets data from a resource.
     *
     * @param integer $pageid
     * @param integer $active
     * @param string $fields
     * @return mixed
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getPageInfo($pageid=-1, $active=1, $fields='id, pagetitle, description, alias, class_key, context_key') {
        $data = false;
        $pageid = intval($pageid);
        if ($pageid == -1) {
            $pageid = is_object($this->resource) && $this->resource->id > 0 ? $this->resource->id : 0;
        }
        if ($pageid > 0) {
            $criteria= $this->newQuery('modResource');
            $criteria->select($fields);
            $criteria->where(array(
                'id' => $pageid,
                'published' => !empty($active) ? 1 : '0',
                'deleted' => '0'
            ));
            if ($obj= $this->getObject('modResource', $criteria)) {
                $data= $obj->toArray();
            }
            if ($data == null) $data = false;
        }
        return $data;
    }

    /**
     * Returns an array of user data.
     *
     * @param integer $uid
     * @return mixed
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getUserInfo($uid) {
        $userInfo= false;
        if ($user= $this->getObject('modUser', $uid, true)) {
            $userInfo= $user->get(array ('username', 'password'));
            if ($userProfile= $user->getOne('modUserProfile')) {
                $userInfo= array_merge($userInfo, $userProfile->toArray());
            }
        }
        return $userInfo;
    }

    /**
     * Returns an array of web user data.
     *
     * @param integer $uid
     * @return mixed
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getWebUserInfo($uid) {
        $userInfo= $this->getUserInfo($uid);
        return $userInfo;
    }

    /**
     * Alias of getUserDocGroups().
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function getDocGroups() {
        $docGroups= $this->getUserDocGroups();
        return $docGroups;
    }

    /**
     * Alias of changePassword().
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function changeWebUserPassword($oldPwd, $newPwd) {
        $rt= $this->changePassword($oldPwd, $newPwd);
        return $rt;
    }

    /**
     * Change current user's password.
     *
     * @param string $o The old password.
     * @param string $n The new password.
     * @return mixed true if successful, otherwise return error message.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function changePassword($o, $n) {
        $rt= false;
        if ($this->getUser()) {
            $rt= $this->user->changePassword($n, $o);
        }
        return $rt;
    }

    /**
     * Cleans the document request parameter.
     *
     * @access private
     * @param string|integer $qOrig
     * @return string|integer
     * @deprecated 2007-09-17 TO be removed in 2.1
     */
    function cleanDocumentIdentifier($qOrig) {
        if (!$this->getRequest()) {
            $this->log(MODX_LOG_LEVEL_FATAL, 'Could not load request class.');
        }
        $return= $this->request->_cleanResourceIdentifier($qOrig);
        return $return;
    }

    /**
     * Gets the identifier specifying a requested document.
     *
     * @param string $method 'id' or 'alias'.
     * @return string|integer The requested document alias or id.
     * @deprecated 2007-09-17 TO be removed in 2.1
     */
    function getDocumentIdentifier($method) {
        if (!$this->getRequest()) {
            $this->log(MODX_LOG_LEVEL_FATAL, 'Could not load request class.');
        }
        return $this->request->getResourceIdentifier($method);
    }

    /**
     * Gets the method being used to request a document.
     *
     * @return string 'id', 'alias', or 'none'.
     * @deprecated 2007-09-17 TO be removed in 2.1
     */
    function getDocumentMethod() {
        if (!$this->getRequest()) {
            $this->log(MODX_LOG_LEVEL_FATAL, 'Could not load request class.');
        }
        return $this->request->getResourceMethod();
    }

    /**
     * Checks to see if the preview parameter is set.
     *
     * @return boolean
     * @deprecated 2007-09-17 TO be removed in 2.1
     */
    function checkPreview() {
        $preview= false;
        if ($this->getResponse()) {
            $preview= $this->response->checkPreview();
        }
        return $preview;
    }

    /**
     * Returns true if user has the specified policy permission.
     *
     * @param string $pm Permission key to check.
     * @return boolean
     */
    function hasPermission($pm) {
        $state = $this->context->checkPolicy($pm);
        return $state;
    }

    /**
     * Add an a alert message to the system event log
     *
     * @param integer $evtid
     * @param integer $type 1 = information, 2 = warning, 3 = error.
     * @param string $msg
     * @param string $source Default is 'Parser'.
     * @deprecated 2007-09-17 TO be removed in 1.0
     */
    function logEvent($evtid,$type,$msg,$source='Parser') {
        $eventLog= $this->newObject('modEventLog');
        $evtid = intval($evtid);
        if ($type<1) $type = 1; else if($type>3) $type = 3;
        $user= $this->getLoginUserID();
        $eventLog->set('eventid', $evtid);
        $eventLog->set('type', $type);
        $eventLog->set('createdon', time());
        $eventLog->set('source', $source);
        $eventLog->set('description', $msg);
        $eventLog->set('user', $user);
        $ds = $eventLog->save(false);
        if(!$ds) {
            echo "Error while inserting event log into database.";
            exit;
        }
    }

    /**
     * Logs a manager action.
     * @access public
     * @param string $action The action to pull from the lexicon module.
     * @param string $class_key The class key that the action is being performed
     * on.
     * @param mixed $item The primary key id or array of keys to grab the object
     * with
     * @return modManagerLog The newly created modManagerLog object
     */
    function logManagerAction($action,$class_key,$item) {
        $ml = $this->newObject('modManagerLog');
        $ml->set('user',$this->user->id);
        $ml->set('occurred',date('Y-m-d H:i:s'));
        $ml->set('action',$action);
        $ml->set('class_key',$class_key);
        $ml->set('item',$item);

        if (!$ml->save()) {
            $this->log(XPDO_LOG_LEVEL_ERROR,$this->lexicon('manager_log_err_save'));
            return null;
        }
        return $ml;
    }

    /**
     * Gets the URL for the 'mgr' context.
     *
     * @return string The base URL of the 'mgr' context relative to the
     * web server document root.
     * @deprecated 2007-09-17 Use MODX_MANAGER_URL or modX :: config['manager_url']
     */
    function getManagerPath() {
        return MODX_MANAGER_URL;
    }

    /**
     * Checks if a user is authenticated and returns array of data if so.
     *
     * @return mixed An array of authenticated user data or false.
     * @deprecated 2007-09-17 To be removed in 2.1
     */
    function userLoggedIn() {
        $userdetails = array();
        if($this->isFrontend() && isset($_SESSION['webValidated'])) {
            $userdetails['loggedIn']=true;
            $userdetails['id']=$_SESSION['webInternalKey'];
            $userdetails['username']=$_SESSION['webShortname'];
            $userdetails['usertype']='web';
            return $userdetails;
        }
        else if($this->isBackend() && isset($_SESSION['mgrValidated'])) {
            $userdetails['loggedIn']=true;
            $userdetails['id']=$_SESSION['mgrInternalKey'];
            $userdetails['username']=$_SESSION['mgrShortname'];
            $userdetails['usertype']='manager';
            return $userdetails;
        }
        else {
            return false;
        }
    }

    /**
     * Gets keyword data associated with a document.
     *
     * @param integer $id A document id, defaults to current document id.
     * @return array An array of keyword data for the specified document.
     */
    function getKeywords($id=0) {
        if (intval($id) == 0) {
            $id = intval($this->resource->id);
        }
        $query = new xPDOCriteria("SELECT keywords.keyword FROM " . $this->getTableName('modKeyword') . " AS keywords "
            . "INNER JOIN " . $this->getTableName('modResourceKeyword') . " AS xref "
            . "ON keywords.id=xref.keyword_id WHERE xref.content_id = :id", array(':id' => $id));
        $keywords = array();
        if ($stmt = $query->prepare()) {
            if ($stmt->execute()) {
                $keywords= $stmt->fetchAll(PDO_FETCH_COLUMN);
            }
        }
        return $keywords;
    }

    /**
     * Gets META tag data associated with a document.
     *
     * @param integer $id A document id, defaults to current document id.
     * @return array An array of META tag data for the specified document.
     */
    function getMETATags($id=0) {
        if (intval($id) == 0) {
            $id = intval($this->resource->id);
        }
        $query = new xPDOCriteria("SELECT smt.* ".
               "FROM ".$this->getTableName('modMetatag')." smt ".
               "INNER JOIN ".$this->getTableName('modResourceMetatag')." cmt ON cmt.metatag_id=smt.id ".
               "WHERE cmt.content_id = :id", array(':id' => $id));
        $metatags = array();
        if ($stmt = $query->prepare()) {
            if ($stmt->execute()) {
                while ($row = $stmt->fetch(PDO_FETCH_ASSOC)) {
                    $metatags[$row['name']] = array("tag"=>$row['tag'],"tagvalue"=>$row['tagvalue'],"http_equiv"=>$row['http_equiv']);
                }
            }
        }
        return $metatags;
    }

    /**
     * Processes document content tags.
     * @deprecated 2007-09-17 To be removed in 2.1 - use modParser::processElementTags().
     */
    function mergeDocumentContent($content) {
        $this->getParser();
        $this->parser->processElementTags('', $content, false, false, '[[', ']]', array ('*'));
        return $content;
    }

    /**
     * Processes document setting tags (and placeholders).
     * @deprecated 2007-09-17 To be removed in 2.1 - use modParser::processElementTags().
     */
    function mergeSettingsContent($content) {
        $this->getParser();
        $this->parser->processElementTags('', $content, false, false, '[[', ']]', array ('+'));
        return $content;
    }

    /**
     * Processes chunk content tags.
     * @deprecated 2007-09-17 To be removed in 2.1 - use modParser::processElementTags().
     */
    function mergeChunkContent($content) {
        $this->getParser();
        $this->parser->processElementTags('', $content, false, false, '[[', ']]', array ('$'));
        return $content;
    }

    /**
     * Processes placeholder content tags.
     * @deprecated 2007-09-17 To be removed in 2.1 - use modParser::processElementTags().
     */
    function mergePlaceholderContent($content) {
        $this->getParser();
        $this->parser->processElementTags('', $content, false, false, '[[', ']]', array ('+'));
        return $content;
    }

    /**
     * Remove an event from the eventMap so it will not be invoked.
     *
     * @param string $event
     * @return boolean false if the event parameter is not specified or is not
     * present in the eventMap.
     */
    function removeEventListener($event) {
        $removed = false;
        if (!empty($event) && isset($this->eventMap[$event])) {
            unset ($this->eventMap[$event]);
            $removed = true;
        }
        return $removed;
    }

    /**
     * Remove all registered events for the current request.
     */
    function removeAllEventListener() {
        unset ($this->eventMap);
        $this->eventMap= array ();
    }

    /**
     * Indicates if modX is executing in the default mgr context.
     *
     * @deprecated 2007-09-15: Use the context key to identify a specific context.
     * @return boolean true if the context is 'mgr'.
     */
    function isBackend() {
        return $this->insideManager() ? true : false;
    }

    /**
     * Indicates if modX is executing in a context other than mgr.
     *
     * @deprecated 2007-09-15: Use the context key to identify a specific context.
     * @return boolean true if the context is not 'mgr'.
     */
    function isFrontend() {
        return !$this->insideManager() ? true : false;
    }

    /**
     * Indicates if the request is taking place inside the mgr context.
     *
     * @deprecated 2007-09-15: Use the context key to identify a specific context.
     * @return boolean true if the request is executing in the mgr context.
     */
    function insideManager() {
        return is_object($this->context) && ($this->context->get('key') === 'mgr');
    }

    /**
     * Add a plugin to the eventMap within the current execution cycle.
     *
     * @param string $event Name of the event.
     * @param integer $pluginId Plugin identifier to add to the event.
     * @return boolean true if the event is successfully added, otherwise false.
     */
    function addEventListener($event, $pluginId) {
        $added = false;
        if ($event && $pluginId) {
            if (!isset($this->eventMap[$event]) || empty ($this->eventMap[$event])) {
                $this->eventMap[$event]= array();
            }
            $this->eventMap[$event][]= $pluginId;
            $added= true;
        }
        return $added;
    }

    /**
     * Alias of getChunk.
     *
     * @deprecated 9/15/2007 Use getChunk instead; to be removed in 2.1.
     */
    function putChunk($chunkName) {
        return $this->getChunk($chunkName);
    }

    /**
     * Switches the primary Context for the modX instance.
     *
     * Be aware that switching contexts does not allow custom session handling
     * classes to be loaded. The gateway defines the session handling that is
     * applied to a single request. To create a context with a custom session
     * handler you must create a unique context gateway that initializes that
     * context directly.
     *
     * @param string $contextKey The key of the context to switch to.
     * @return boolean True if the switch was successful, otherwise false.
     */
    function switchContext($contextKey) {
        $switched= false;
        if ($this->context->key != $contextKey) {
            $switched= $this->_initContext($contextKey);
        }
        return $switched;
    }

    /**
     * Retrieve a context by name without initializing it.
     *
     * Within a request, contexts retrieved using this function will cache the
     * context data into the modX::$contexts array to avoid loading the same
     * context multiple times.
     *
     * @access public
     * @param string $contextKey The context to retrieve.
     * @return &$modContext A modContext object retrieved from cache or
     * database.
     */
    function getContext($contextKey) {
        if (!isset($this->contexts[$contextKey])) {
            $this->contexts[$contextKey]= $this->getObject('modContext', $contextKey);
            if ($this->contexts[$contextKey]) {
                $this->contexts[$contextKey]->prepare();
            }
        }
        return $this->contexts[$contextKey];
    }

    /**
     * Gets a map of events and registered plugins for the specified context.
     *
     * @param string $contextKey Context identifier.
     * @return array A map of events and registered plugins for each.
     */
    function getEventMap($contextKey) {
        $eventElementMap= array ();
        if ($contextKey) {
            switch ($contextKey) {
                case 'mgr':
                    $service= "ev.`service` IN (1,2,4,5,6) AND";
                    break;
                default:
                    $service= "ev.`service` IN (1,3,4,5,6) AND (ev.`groupname` = '' OR ev.`groupname` = 'RichText Editor' OR ev.`groupname` = 'modUser') AND";
            }
            $eeTbl= $this->getTableName('modPluginEvent');
            $eventTbl= $this->getTableName('modEvent');
            $pluginTbl= $this->getTableName('modPlugin');
            $propsetTbl= $this->getTableName('modPropertySet');
            $sql= "SELECT ev.`name` AS `event`, ee.`pluginid`, ps.`name` AS `propertyset` FROM {$eeTbl} ee INNER JOIN {$pluginTbl} pl ON pl.`id` = ee.`pluginid` AND pl.`disabled` = 0 INNER JOIN {$eventTbl} ev ON {$service} ev.`id` = ee.`evtid` LEFT JOIN {$propsetTbl} ps ON ee.`propertyset` = ps.`id` ORDER BY ev.`name`, ee.`priority` ASC";
            $stmt= $this->prepare($sql);
            if ($stmt && $stmt->execute()) {
                while ($ee = $stmt->fetch(PDO_FETCH_ASSOC)) {
                    $eventElementMap[$ee['event']][(string) $ee['pluginid']]= $ee['pluginid'] . (!empty($ee['propertyset']) ? ':' . $ee['propertyset'] : '');
                }
            }
        }
        return $eventElementMap;
    }

    /**
     * Checks for locking on a page.
     *
     * @param integer $id Id of the user checking for a lock.
     * @param string $action The action identifying what is locked.
     * @param string $type Message indicating the kind of lock being checked.
     */
    function checkForLocks($id,$action,$type) {
        $msg= false;
        $id= intval($id);
        if (!$id) $id= $this->getLoginUserID();
        if ($au = $this->getObject('modActiveUser',array('action' => $action, 'internalKey:!=' => $id))) {
            $msg = $this->lexicon('lock_msg',array(
                'name' => $au->get('username'),
                'object' => $type,
            ));
        }
        return $msg;
    }

    /**
     * Grabs a processed lexicon string.
     *
     * @access public
     * @param string $key
     * @param array $params
     */
    function lexicon($key,$params = array()) {
        if ($this->lexicon) {
            return $this->lexicon->process($key,$params);
        } else {
            $this->log(XPDO_LOG_LEVEL_ERROR,'Culture not initialized; cannot use lexicon.');
        }
    }

    /**
     * Returns the state of the SESSION being used by modX.
     *
     * The possible values for session state are:
     *
     * MODX_SESSION_STATE_UNINITIALIZED
     * MODX_SESSION_STATE_UNAVAILABLE
     * MODX_SESSION_STATE_EXTERNAL
     * MODX_SESSION_STATE_INITIALIZED
     *
     * @return integer Returns an integer representing the session state.
     */
    function getSessionState() {
        if ($this->_sessionState == MODX_SESSION_STATE_UNINITIALIZED) {
            if (XPDO_CLI_MODE) {
                $this->_sessionState = MODX_SESSION_STATE_UNAVAILABLE;
            }
            elseif (isset($_SESSION)) {
                $this->_sessionState = MODX_SESSION_STATE_EXTERNAL;
            }
        }
        return $this->_sessionState;
    }

    /**
     * Executed before parser processing of an element.
     *
     * @access protected
     */
    function _beforeProcessing() {
        $this->documentIdentifier= & $this->resourceIdentifier;
        $this->documentMethod= & $this->resourceMethod;
        $this->documentContent= & $this->resource->_content;
        $this->documentGenerated= & $this->resourceGenerated;
        $this->dbConfig= & $this->db->config;
    }

    /**
     * Executed before the response is rendered.
     *
     * @access protected
     */
    function _beforeRender() {
        $this->documentOutput= & $this->resource->_output;
    }

    /**
     * Executed before the handleRequest function.
     *
     * @access protected
     */
    function _beforeRequest() {}

    /**
     * Determines the current site_status.
     *
     * @return boolean True if the site is online or the user has a valid
     * user session in the 'mgr' context; false otherwise.
     */
    function _checkSiteStatus() {
        $status = false;
        if ($this->config['site_status'] == '1' || $this->hasPermission('view_offline')) {
            $status = true;
        }
        return $status;
    }

    /**
     * Loads a specified Context.
     *
     * Merges any context settings with the modX::$config, and performs any
     * other context specific initialization tasks.
     *
     * @access protected
     * @param string $contextKey A context identifier.
     */
    function _initContext($contextKey) {
        $initialized= false;
        if (isset($this->contexts[$contextKey])) {
            $this->context= & $this->contexts[$contextKey];
        } else {
            $this->context= $this->newObject('modContext');
            $this->context->_fields['key']= $contextKey;
        }
        if ($this->context) {
            if (!$this->context->prepare()) {
                $this->log(MODX_LOG_LEVEL_ERROR, 'Could not load context: ' . $contextKey);
            } else {
                $this->aliasMap= & $this->context->aliasMap;
                $this->resourceMap= & $this->context->resourceMap;
                $this->documentMap= & $this->context->documentMap;
                $this->resourceListing= & $this->context->resourceListing;
                $this->documentListing= & $this->context->documentListing;
                $this->eventMap= & $this->context->eventMap;
                $this->pluginCache= & $this->context->pluginCache;
                $this->config= array_merge($this->_systemConfig, $this->context->config);
                if ($this->_initialized) {
                    $this->getUser();
                }
                $initialized= true;
            }
        }
        return $initialized;
    }

    /**
     * Initializes the culture settings.
     *
     * @access protected
     */
    function _initCulture() {
        global $_lang;
        $this->getService('lexicon','modLexicon');
        $this->invokeEvent('OnInitCulture');
    }

    /**
     * Loads the error handler for this instance.
     * @access protected
     */
    function _initErrorHandler() {
        if ($this->errorHandler == null || !is_object($this->errorHandler)) {
            if (isset ($this->config['error_handler_class']) && strlen($this->config['error_handler_class']) > 1) {
                if ($ehClass= $this->loadClass($this->config['error_handler_class'], '', false, true)) {
                    if ($this->errorHandler= new $ehClass($this)) {
                        $result= set_error_handler(array ($this->errorHandler, 'handleError'));
                        if ($result === false) {
                            $this->log(XPDO_LOG_LEVEL_ERROR, 'Could not set error handler.  Make sure your class has a function called handleError(). Result: ' . print_r($result, true));
                        }
                    }
                }
            }
        }
    }

    /**
     * Populates the map of events and registered plugins for each.
     *
     * @access protected
     * @param string $contextKey Context identifier.
     */
    function _initEventMap($contextKey) {
        if ($this->eventMap === null) {
            $this->eventMap= $this->getEventMap($contextKey);
        }
    }

    /**
     * Loads the session handler and starts the session.
     * @access protected
     */
    function _initSession() {
        $contextKey= $this->context->get('key');
        if ($this->getSessionState() == MODX_SESSION_STATE_UNINITIALIZED) {
            $sh= false;
            if (isset ($this->config['session_handler_class']) && $this->config['session_handler_class']) {
                if ($shClass= $this->loadClass($this->config['session_handler_class'], '', false, true)) {
                    if ($sh= new $shClass($this)) {
                        session_set_save_handler(
                            array (& $sh, 'open'),
                            array (& $sh, 'close'),
                            array (& $sh, 'read'),
                            array (& $sh, 'write'),
                            array (& $sh, 'destroy'),
                            array (& $sh, 'gc')
                        );
                    }
                }
            }
            if (!$sh) {
                if (isset ($this->config['session_save_path']) && is_writable($this->config['session_save_path'])) {
                    session_save_path($this->config['session_save_path']);
                }
            }
            if (isset ($this->config['session_gc_maxlifetime'])) {
                ini_set('session.gc_maxlifetime', (integer) $this->config['session_gc_maxlifetime']);
            }
            $cookieLifetime= 0;
            if (isset ($this->config['session_cookie_lifetime'])) {
                $cookieLifetime= intval($this->config['session_cookie_lifetime']);
            }
            $cookiePath= isset ($this->config['session_cookie_path']) ? $this->config['session_cookie_path'] : $this->config['base_path'];
            ini_set('session.cookie_lifetime', $cookieLifetime);
            ini_set('session.cookie_path', $cookiePath);
            $site_sessionname= isset ($this->config['session_name']) ? $this->config['session_name'] : $GLOBALS['site_sessionname'];
            session_name($site_sessionname);
            session_start();
            $this->_sessionState = MODX_SESSION_STATE_INITIALIZED;
            $this->getUser($contextKey);
            $cookieExpiration= 0;
            if (isset ($_SESSION['modx.' . $contextKey . '.session.cookie.lifetime']) && is_numeric($_SESSION['modx.' . $contextKey . '.session.cookie.lifetime'])) {
                $cookieLifetime= intval($_SESSION['modx.' . $contextKey . '.session.cookie.lifetime']);
                if ($cookieLifetime) {
                    $cookieExpiration= time() + $cookieLifetime;
                }
                setcookie(session_name(), session_id(), $cookieExpiration, $cookiePath);
            }
        }
    }

    /**
     * Loads the modX system configuration settings.
     *
     * @access protected
     * @return boolean True if successful.
     */
    function _loadConfig() {
        $this->config= $this->_config;

        $this->getCacheManager();
        if (!$config = $this->cacheManager->get('config')) {
            $config = $this->cacheManager->generateConfig();
        }
        if (empty($config)) {
            $config = array();
            if (!$settings= $this->getCollection('modSystemSetting')) {
                return false;
            }
            foreach ($settings as $setting) {
                $config[$setting->get('key')]= $setting->get('value');
            }
        }
        $this->config = array_merge($this->config, $config);
        $this->_systemConfig= $this->config;
        return true;
    }

    /**
     * Provides modX the ability to use modRegister instances as log targets.
     *
     * {@inheritdoc}
     */
    function _log($level, $msg, $target= '', $def= '', $file= '', $line= '') {
        if (empty($target)) {
            $target = $this->logTarget;
        }
        $targetOptions = array();
        if (is_array($target)) {
            if (isset($target['options'])) $targetOptions = $target['options'];
            $target = isset($target['target']) ? $target['target'] : 'ECHO';
        }
        if (is_object($target) && is_a($target, 'modRegister')) {
            if ($level === XPDO_LOG_LEVEL_FATAL) {
                if (empty ($file)) $file= (isset ($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : (isset ($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '');
                $this->_logInRegister($target, $level, $msg, $def, $file, $line);
                $this->sendError('fatal');
            }
            if ($this->_debug === true || $level <= $this->logLevel) {
                if (empty ($file)) $file= (isset ($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : (isset ($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '');
                $this->_logInRegister($target, $level, $msg, $def, $file, $line);
            }
        } else {
            if ($level === XPDO_LOG_LEVEL_FATAL) {
                while (@ob_end_clean()) {}
                if ($target == 'FILE' && $cacheManager= $this->getCacheManager()) {
                    $filename = isset($targetOptions['filename']) ? $targetOptions['filename'] : 'error.log';
                    $filepath = isset($targetOptions['filepath']) ? $targetOptions['filepath'] : $this->getCachePath() . XPDO_LOG_DIR;
                    $cacheManager->writeFile($filepath . $filename, '[' . strftime('%Y-%m-%d %H:%M:%S') . '] (' . $this->_getLogLevel($level) . $def . $file . $line . ') ' . $msg . "\n" . ($this->getDebug() === true ? '<pre>' . "\n" . print_r(debug_backtrace(), true) . "\n" . '</pre>' : ''), 'a');
                }
                $this->sendError('fatal');
            }
            parent :: _log($level, $msg, $target, $def, $file, $line);
        }
    }

    /**
     * Provides custom logging functionality for modRegister targets.
     *
     * @access protected
     */
    function _logInRegister($register, $level, $msg, $def, $file, $line) {
        $timestamp = strftime('%Y-%m-%d %H:%M:%S');
        $messageKey = (string) time();
        $messageKey .= '-' . sprintf("%06d", $this->_logSequence);
        $message = array(
            'timestamp' => $timestamp,
            'level' => $this->_getLogLevel($level),
            'msg' => $msg,
            'def' => $def,
            'file' => $file,
            'line' => $line
        );
        $options = array();
        if ($level === XPDO_LOG_LEVEL_FATAL) {
            $options['kill'] = true;
        }
        $register->send('', array($messageKey => $message), $options);
        $this->_logSequence++;
    }

    /**
     * Executed after the response is sent and execution is completed.
     *
     * @access protected
     */
    function _postProcess() {
        if ($this->getOption('cache_resource', array(), true)) {
            if (is_object($this->resource) && $this->resource->get('cacheable')) {
                $this->invokeEvent('OnBeforeSaveWebPageCache');
                $this->cacheManager->generateResource($this->resource);
            }
        }
        $this->invokeEvent('OnWebPageComplete');
    }
}

/**
 * Represents a modEvent when invoking events.
 * @package modx
 */
class modSystemEvent {
    /**@#+
     * @deprecated 2007-09-18 Will be delegated in 1.0 or sooner.
     * @var string
     */
    var $name = '';
    var $activePlugin = '';
    var $propertySet = '';
    /**
     * @var boolean
     */
    var $_propagate;
    var $_output;
    /**
     * @var boolean
     */
    var $activated;
    /**
     * @var mixed
     */
    var $returnedValues;
    /**
     * @var array
     */
    var $params;
    /**@#-*/

    /**
     * Display a message to the user during the event.
     *
     * @todo Remove this; the centralized modRegistry will handle configurable
     * logging of any kind of message or data to any repository or output
     * context.  Use {@link modX::_log()} in the meantime.
     * @param string $msg The message to display.
     */
    function alert($msg) {
        global $SystemAlertMsgQueque;
        if($msg=="") return;
        if (is_array($SystemAlertMsgQueque)) {
            if($this->name && $this->activePlugin) $title = "<div><b>".$this->activePlugin."</b> - <span style='color:maroon;'>".$this->name."</span></div>";
            $SystemAlertMsgQueque[] = "$title<div style='margin-left:10px;margin-top:3px;'>$msg</div>";
        }
    }

    /**
     * Render output from the event.
     * @param string $output The output to render.
     */
    function output($output) {
        $this->_output .= $output;
    }

    /**
     * Stop further execution of plugins for this event.
     */
    function stopPropagation(){
        $this->_propagate = false;
    }

    /**
     * Reset the event instance for reuse.
     */
    function _resetEventObject(){
        $this->returnedValues = null;
        $this->name = "";
        $this->_output = "";
        $this->_propagate = true;
        $this->activated = false;
    }
}
?>