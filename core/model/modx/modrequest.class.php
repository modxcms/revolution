<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
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
 * @package modx
 */
/**
 * Encapsulates the interaction of MODX with an HTTP request.
 *
 * This class represents the functional portion of the MODX {@link
 * http://www.martinfowler.com/eaaCatalog/frontController.html front-
 * controller}, and is responsible for sanitizing, interpretting, and
 * dispatching a web request to the appropriate MODX {@link modResource
 * Web Resource}.
 *
 * @package modx
 */
class modRequest {
    /**
     * A reference to the modX object
     * @var modX $modx
     */
    public $modx = null;
    /**
     * The current request method
     * @var string $method
     */
    public $method = null;
    /**
     * The parameters sent in the request
     * @var array $parameters
     */
    public $parameters = null;
    /**
     * The HTTP headers sent in the request
     * @var array $headers
     */
    public $headers = null;

    /**
     * @param modX $modx A reference to the modX object
     */
    function __construct(modX &$modx) {
        $this->modx = & $modx;
        $this->parameters['GET'] =& $_GET;
        $this->parameters['POST'] =& $_POST;
        $this->parameters['COOKIE'] =& $_COOKIE;
        $this->parameters['REQUEST'] =& $_REQUEST;
    }

    /**
     * The primary MODX request handler (a.k.a. controller).
     *
     * @return boolean True if a request is handled without interruption.
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        $this->sanitizeRequest();
        $this->modx->invokeEvent('OnHandleRequest');
        if (!$this->modx->checkSiteStatus()) {
            header('HTTP/1.1 503 Service Unavailable');
            if (!$this->modx->getOption('site_unavailable_page',null,1)) {
                $this->modx->resource = $this->modx->newObject('modDocument');
                $this->modx->resource->template = 0;
                $this->modx->resource->content = $this->modx->getOption('site_unavailable_message');
            } else {
                $this->modx->resourceMethod = "id";
                $this->modx->resourceIdentifier = $this->modx->getOption('site_unavailable_page',null,1);
            }
        } else {
            $this->checkPublishStatus();
            $this->modx->resourceMethod = $this->getResourceMethod();
            $this->modx->resourceIdentifier = $this->getResourceIdentifier($this->modx->resourceMethod);
            if ($this->modx->resourceMethod == 'id' && $this->modx->getOption('friendly_urls', null, false) && !$this->modx->getOption('request_method_strict', null, false)) {
                $uri = array_search($this->modx->resourceIdentifier, $this->modx->aliasMap);
                if (!empty($uri)) {
                    if ($this->modx->resourceIdentifier == $this->modx->getOption('site_start', null, 1)) {
                        $url = $this->modx->getOption('site_url', null, MODX_SITE_URL);
                    } else {
                        $url = $this->modx->getOption('site_url', null, MODX_SITE_URL) . $uri;
                    }
                    $this->modx->sendRedirect($url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
                }
            }
        }
        if (empty ($this->modx->resourceMethod)) {
            $this->modx->resourceMethod = "id";
        }
        if ($this->modx->resourceMethod == "alias") {
            $this->modx->resourceIdentifier = $this->_cleanResourceIdentifier($this->modx->resourceIdentifier);
        }
        if ($this->modx->resourceMethod == "alias") {
            if (isset ($this->modx->aliasMap[$this->modx->resourceIdentifier])) {
                $this->modx->resourceIdentifier = $this->modx->aliasMap[$this->modx->resourceIdentifier];
                $this->modx->resourceMethod = 'id';
            } else {
                $this->modx->sendErrorPage();
            }
        }
        $this->modx->beforeRequest();
        $this->modx->invokeEvent("OnWebPageInit");

        if (!is_object($this->modx->resource)) {
            if (!$this->modx->resource = $this->getResource($this->modx->resourceMethod, $this->modx->resourceIdentifier)) {
                $this->modx->sendErrorPage();
                return true;
            }
        }

        return $this->prepareResponse();
    }

    /**
     * Prepares the MODX response to a web request that is being handled.
     *
     * @param array $options An array of options
     * @return boolean True if the response is properly prepared.
     */
    public function prepareResponse(array $options = array()) {
        $this->modx->beforeProcessing();
        $this->modx->invokeEvent("OnLoadWebDocument");

        if (!$this->modx->getResponse()) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, 'Could not load response class.');
        }
        $this->modx->response->outputContent($options);
    }

    /**
     * Gets the method used to request a resource.
     *
     * @return string 'alias', 'id', or an empty string.
     */
    public function getResourceMethod() {
        $method = '';
        $hasId = isset($_REQUEST[$this->modx->getOption('request_param_id',null,'id')]);
        $hasAlias = isset($_REQUEST[$this->modx->getOption('request_param_alias',null,'q')]);
        if ($hasId || $hasAlias) {
            if ($this->modx->getOption('request_method_strict', null, false)) {
                $method = $this->modx->getOption('friendly_urls', null, false) ? 'alias' : 'id';
            } elseif ($hasAlias) {
                $method = "alias";
            } elseif ($hasId) {
                $method = "id";
            }
        }
        return $method;
    }

    /**
     * Gets a requested resource and all required data.
     *
     * @param string $method The method, 'id', or 'alias', by which to perform
     * the resource lookup.
     * @param string|integer $identifier The identifier with which to search.
     * @param array $options An array of options for the resource fetching
     * @return modResource The requested modResource instance or request
     * is forwarded to the error page, or unauthorized page.
     */
    public function getResource($method, $identifier, array $options = array()) {
        $resource = null;
        if ($method == 'alias') {
            $resourceId = $this->modx->aliasMap[$identifier];
        } else {
            $resourceId = $identifier;
        }

        if (!is_numeric($resourceId)) {
            $this->modx->sendErrorPage();
        }
        $isForward = array_key_exists('forward', $options) && !empty($options['forward']);
        $fromCache = false;
        $cacheKey = $this->modx->context->get('key') . "/resources/{$resourceId}";
        $cachedResource = $this->modx->cacheManager->get($cacheKey, array(
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_resource_key', null, 'resource'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_resource_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $this->modx->getOption('cache_resource_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));
        if (is_array($cachedResource) && array_key_exists('resource', $cachedResource) && is_array($cachedResource['resource'])) {
            /** @var modResource $resource */
            $resource = $this->modx->newObject($cachedResource['resourceClass']);
            if ($resource) {
                $resource->fromArray($cachedResource['resource'], '', true, true, true);
                $resource->_content = $cachedResource['resource']['_content'];
                $resource->_isForward = isset($cachedResource['resource']['_isForward']) && !empty($cachedResource['resource']['_isForward']);
                if (isset($cachedResource['contentType'])) {
                    $contentType = $this->modx->newObject('modContentType');
                    $contentType->fromArray($cachedResource['contentType'], '', true, true, true);
                    $resource->addOne($contentType, 'ContentType');
                }
                if (isset($cachedResource['resourceGroups'])) {
                    $rGroups = array();
                    foreach ($cachedResource['resourceGroups'] as $rGroupKey => $rGroup) {
                        $rGroups[$rGroupKey]= $this->modx->newObject('modResourceGroupResource', $rGroup);
                    }
                    $resource->addMany($rGroups);
                }
                if (isset($cachedResource['policyCache'])) $resource->setPolicies(array($this->modx->context->get('key') => $cachedResource['policyCache']));
                if (isset($cachedResource['elementCache'])) $this->modx->elementCache = $cachedResource['elementCache'];
                if (isset($cachedResource['sourceCache'])) $this->modx->sourceCache = $cachedResource['sourceCache'];
                if ($resource->get('_jscripts')) $this->modx->jscripts = $this->modx->jscripts + $resource->get('_jscripts');
                if ($resource->get('_sjscripts')) $this->modx->sjscripts = $this->modx->sjscripts + $resource->get('_sjscripts');
                if ($resource->get('_loadedjscripts')) $this->modx->loadedjscripts = array_merge($this->modx->loadedjscripts, $resource->get('_loadedjscripts'));
                $isForward= $resource->_isForward;
                $resource->setProcessed(true);
                $fromCache = true;
            }
        }
        if (!$fromCache || !is_object($resource)) {
            $criteria = $this->modx->newQuery('modResource');
            $criteria->select(array($this->modx->escape('modResource').'.*'));
            $criteria->where(array('id' => $resourceId, 'deleted' => '0'));
            if (!$this->modx->hasPermission('view_unpublished') || $this->modx->getSessionState() !== modX::SESSION_STATE_INITIALIZED) {
                $criteria->where(array('published' => 1));
            }
            if ($resource = $this->modx->getObject('modResource', $criteria)) {
                if ($resource instanceof modResource) {
                    if ($resource->get('context_key') !== $this->modx->context->get('key')) {
                        if (!$isForward || ($isForward && !$this->modx->getOption('allow_forward_across_contexts', $options, false))) {
                            if (!$this->modx->getCount('modContextResource', array($this->modx->context->get('key'), $resourceId))) {
                                return null;
                            }
                        }
                    }
                    $resource->_isForward= $isForward;
                    if (!$resource->checkPolicy('view')) {
                        $this->modx->sendUnauthorizedPage();
                    }
                    if ($tvs = $resource->getMany('TemplateVars', 'all')) {
                        /** @var modTemplateVar $tv */
                        foreach ($tvs as $tv) {
                            $resource->set($tv->get('name'), array(
                                $tv->get('name'),
                                $tv->getValue($resource->get('id')),
                                $tv->get('display'),
                                $tv->get('display_params'),
                                $tv->get('type'),
                            ));
                        }
                    }
                    $this->modx->resourceGenerated = true;
                }
            }
        } elseif ($fromCache && $resource instanceof modResource && !$resource->get('deleted')) {
            if ($resource->checkPolicy('load') && ($resource->get('published') || ($this->modx->getSessionState() === modX::SESSION_STATE_INITIALIZED && $this->modx->hasPermission('view_unpublished')))) {
                if ($resource->get('context_key') !== $this->modx->context->get('key')) {
                    if (!$isForward || ($isForward && !$this->modx->getOption('allow_forward_across_contexts', $options, false))) {
                        if (!$this->modx->getCount('modContextResource', array($this->modx->context->get('key'), $resourceId))) {
                            return null;
                        }
                    }
                }
                if (!$resource->checkPolicy('view')) {
                    $this->modx->sendUnauthorizedPage();
                }
            } else {
                return null;
            }
            $this->modx->invokeEvent('OnLoadWebPageCache');
        }
        return $resource;
    }

    /**
     * Gets the idetifier used to request a resource.
     *
     * @param string $method 'alias' or 'id'.
     * @return string The identifier for the requested resource.
     */
    public function getResourceIdentifier($method) {
        $identifier = '';
        switch ($method) {
            case 'alias' :
                $rAlias = $this->modx->getOption('request_param_alias', null, 'q');
                $identifier = isset ($_REQUEST[$rAlias]) ? $_REQUEST[$rAlias] : $identifier;
                break;
            case 'id' :
                $rId = $this->modx->getOption('request_param_id', null, 'id');
                $identifier = isset ($_REQUEST[$rId]) ? $_REQUEST[$rId] : $identifier;
                break;
            default :
                $identifier = $this->modx->getOption('site_start', null, 1);
        }
        return $identifier;
    }

    /**
     * Cleans the resource identifier from the request params.
     *
     * @param string $identifier The raw identifier.
     * @return string|integer The cleansed identifier.
     */
    public function _cleanResourceIdentifier($identifier) {
        if (empty ($identifier)) {
            if ($this->modx->getOption('base_url', null, MODX_BASE_URL) !== $_SERVER['REQUEST_URI']) {
                $this->modx->sendRedirect($this->modx->getOption('site_url', null, MODX_SITE_URL), array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
            }
            $identifier = $this->modx->getOption('site_start', null, 1);
            $this->modx->resourceMethod = 'id';
        }
        elseif ($this->modx->getOption('friendly_urls', null, false) && $this->modx->resourceMethod = 'alias') {
            $containerSuffix = trim($this->modx->getOption('container_suffix', null, ''));
            if (!isset ($this->modx->aliasMap[$identifier])) {
                if (!empty ($containerSuffix)) {
                    $suffixLen = strlen($containerSuffix);
                    $identifierLen = strlen($identifier);
                    if (substr($identifier, $identifierLen - $suffixLen) === $containerSuffix) {
                        $identifier = substr($identifier, 0, $identifierLen - $suffixLen);
                    }
                    elseif (isset ($this->modx->aliasMap["{$identifier}{$containerSuffix}"])) {
                        $identifier = "{$identifier}{$containerSuffix}";
                    }
                    if (isset ($this->modx->aliasMap[$identifier])) {
                        $parameters = $this->getParameters();
                        unset($parameters[$this->modx->getOption('request_param_alias')]);
                        $url = $this->modx->makeUrl($this->modx->aliasMap[$identifier], '', $parameters, 'full');
                        $this->modx->sendRedirect($url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
                    }
                    $this->modx->resourceMethod = 'alias';
                }
            }
            elseif ($this->modx->getOption('site_start', null, 1) == $this->modx->aliasMap[$identifier]) {
                $parameters = $this->getParameters();
                unset($parameters[$this->modx->getOption('request_param_alias')]);
                $url = $this->modx->makeUrl($this->modx->getOption('site_start', null, 1), '', $parameters, 'full');
                $this->modx->sendRedirect($url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
            } else {
                if ($this->modx->getOption('friendly_urls_strict', null, false)) {
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $qsPos = strpos($requestUri, '?');
                    if ($qsPos !== false) $requestUri = substr($requestUri, 0, $qsPos);
                    $fullId = $this->modx->getOption('base_url', null, MODX_BASE_URL) . $identifier;
                    $requestUri = urldecode($requestUri);
                    if ($fullId !== $requestUri && strpos($requestUri, $fullId) !== 0) {
                        $parameters = $this->getParameters();
                        unset($parameters[$this->modx->getOption('request_param_alias')]);
                        $url = $this->modx->makeUrl($this->modx->aliasMap[$identifier], '', $parameters, 'full');
                        $this->modx->sendRedirect($url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
                    }
                }
                $this->modx->resourceMethod = 'alias';
            }
        } else {
            $this->modx->resourceMethod = 'id';
        }
        return $identifier;
    }

    /**
     * Harden GPC variables by removing any MODX tags, Javascript, or entities.
     */
    public function sanitizeRequest() {
        $modxtags = array_values($this->modx->sanitizePatterns);
        modX :: sanitize($_GET, $modxtags, 0);
        if ($this->modx->getOption('allow_tags_in_post',null,true)) {
            modX :: sanitize($_POST);
        } else {
            modX :: sanitize($_POST, $modxtags);
        }
        modX :: sanitize($_COOKIE, $modxtags);
        modX :: sanitize($_REQUEST, $modxtags);
        $rAlias = $this->modx->getOption('request_param_alias', null, 'q');
        if (isset ($_GET[$rAlias])) {
            $_GET[$rAlias] = preg_replace("/[^A-Za-z0-9_\-\.\/]/", "", $_GET[$rAlias]);
        }
    }

    /**
     * Loads the error handling class for the request.
     *
     * @param string $class The class to use as the error handler.
     */
    public function loadErrorHandler($class = 'modError') {
        if ($className = $this->modx->loadClass('error.'.$class,'',false,true)) {
            $this->modx->error = new $className($this->modx);
        } else {
            $this->modx->log(modX::LOG_LEVEL_FATAL,'Error handling class could not be loaded: '.$class);
        }
    }

    /**
     * Provides an easy way to initiate register logging.
     *
     * Through an array of options, you can have all calls to modX::log()
     * recorded in a topic of a modRegister instance. The options include:
     *
     * <ul>
     * <li>register: the name of the register (required)</li>
     * <li>topic: the topic to record to (required)</li>
     * <li>register_class: the modRegister class (defaults to modFileRegister)</li>
     * <li>log_level: the logging level (defaults to MODX_LOG_LEVEL_INFO)</li>
     * </ul>
     *
     * @param array $options An array containing all the options required to
     * initiate and configure logging to a modRegister instance.
     */
    public function registerLogging(array $options = array()) {
        if (isset($options['register']) && isset($options['topic'])) {
            if ($this->modx->getService('registry','registry.modRegistry')) {
                $register_class = isset($options['register_class']) ? $options['register_class'] : 'registry.modFileRegister';
                $register = $this->modx->registry->getRegister($options['register'], $register_class);
                if ($register) {
                    $level = isset($options['log_level']) ? $options['log_level'] : modX::LOG_LEVEL_INFO;
                    $this->modx->registry->setLogging($register, $options['topic'], $level);
                }
            }
        }
    }

    /**
     * Preserves the $_REQUEST superglobal to the $_SESSION.
     *
     * @param string $key A key to save the $_REQUEST as; default is 'referrer'.
     */
    public function preserveRequest($key = 'referrer') {
        if (isset ($_SESSION)) {
            $_SESSION['modx.request.' . $key] = $_REQUEST;
        }
    }

    /**
     * Retrieve a preserved $_REQUEST from $_SESSION.
     *
     * @param string $key A key to identify a specific $_REQUEST; default is 'referrer'.
     * @return string
     */
    public function retrieveRequest($key = 'referrer') {
        $request = null;
        if (isset ($_SESSION['modx.request.' . $key])) {
            $request = $_SESSION['modx.request.' . $key];
        }
        return $request;
    }

    /**
     * Return the HTTP headers sent through the request
     * @param boolean $ucKeys if true, upper-case all keys for the headers
     * @return array
     */
    public function getHeaders($ucKeys = false) {
        if (!isset($this->headers)) {
            $headers = array ();
            foreach ($_SERVER as $name => $value) {
                if (substr(strtoupper($name), 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            if (!empty($headers) && $ucKeys) {
                $headers = array_change_key_case($headers, CASE_UPPER);
            }
            $this->headers = $headers;
        }
        return $this->headers;
    }

    /**
     * Checks the current status of timed publishing events.
     */
    public function checkPublishStatus() {
        $cacheRefreshTime = (integer) $this->modx->cacheManager->get('auto_publish', array(
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_auto_publish_key', null, 'auto_publish'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_auto_publish_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER))
        ));
        if ($cacheRefreshTime > 0) {
            $timeNow= time() + $this->modx->getOption('server_offset_time', null, 0);
            if ($cacheRefreshTime <= $timeNow) {
                $this->modx->cacheManager->refresh();
            }
        }
    }

    /**
     * Get a list of all modAction IDs
     * @param string $namespace
     * @return array
     */
    public function getAllActionIDs($namespace = '') {
        $c = array();
        if (!empty($namespace)) $c['namespace'] = $namespace;
        $actions = $this->modx->getCollection('modAction',$c);

        $actionList = array();
        /** @var modAction $action */
        foreach ($actions as $action) {
            $key = ($action->get('namespace') == 'core' ? '' : $action->get('namespace').':').$action->get('controller');
            $actionList[$key] = $action->get('id');
        }
        return $actionList;
    }

    /**
     * Get the IDs for a collection of string action keys
     * @param array $actions
     * @param string $namespace
     * @return array
     */
    public function getActionIDs(array $actions = array(), $namespace = 'core') {
        $as = array();
        foreach ($actions as $action) {
            /** @var modAction $actionObject */
            $actionObject = $this->modx->getObject('modAction',array(
                'namespace' => $namespace,
                'controller' => $action,
            ));
            if (empty($actionObject)) {
                $as[$action] = 0;
            } else {
                $as[$action] = $actionObject->get('id');
            }
        }
        return $as;
    }

    /**
     * Get a GPC/REQUEST variable value or an array of values from the request.
     *
     * @param string|array $keys A key or array of keys to retrieve from the GPC variable. An empty
     * array means get all keys of the variable.
     * @param string $type The type of GPC variable, GET by default (GET, POST, COOKIE or REQUEST).
     * @return mixed
     */
    public function getParameters($keys = array(), $type = 'GET') {
        $value = null;
        if (!is_string($type) || !in_array($type, array('GET', 'POST', 'COOKIE', 'REQUEST'))) {
            $type = 'GET';
        }
        if (is_array($keys)) {
            $value = array();
            if (empty($keys) && isset($this->parameters[$type])) {
                $keys = array_keys($this->parameters[$type]);
            }
        }
        if (isset($this->parameters[$type])) {
            $method = '';
            $methodIdentifier = '';
            if ($type == 'GET') {
                $method = $this->getResourceMethod();
                $methodIdentifier = $this->modx->getOption('request_param_' . $method, null,$method);
            }
            if (is_array($keys)) {
                foreach ($keys as $key) {
                    if ($type != 'GET' || $key != $methodIdentifier) {
                        $keyValue = $this->getParameters($key, $type);
                        if ($keyValue !== null) $value[$key] = $keyValue;
                    }
                }
            } else {
                if ($type != 'GET' || $keys != $methodIdentifier) {
                    if (array_key_exists($keys, $this->parameters[$type])) {
                        $value = $this->parameters[$type][$keys];
                    }
                }
            }
        }
        return $value;
    }

    /**
     * Get the true client IP. Returns an array of values:
     * 
     * * ip - The real, true client IP
     * * suspected - The suspected IP, if not alike to REMOTE_ADDR
     * * network - The client's network IP
     *
     * @access public
     * @return array
     */
    public function getClientIp() {
        $ip = '';
        $ipAll = array(); // networks IP
        $ipSus = array(); // suspected IP

        $serverVariables = array(
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_X_COMING_FROM',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_COMING_FROM',
            'HTTP_CLIENT_IP',
            'HTTP_FROM',
            'HTTP_VIA',
            'REMOTE_ADDR',
        );

        foreach ($serverVariables as $serverVariable) {
            $value = '';
            if (isset($_SERVER[$serverVariable])) {
                $value = $_SERVER[$serverVariable];
            } elseif (getenv($serverVariable)) {
                $value = getenv($serverVariable);
            }

            if (!empty($value)) {
                $tmp = explode(',', $value);
                $ipSus[] = $tmp[0];
                $ipAll = array_merge($ipAll,$tmp);
            }
        }

        $ipSus = array_unique($ipSus);
        $ipAll = array_unique($ipAll);
        $ip = (sizeof($ipSus) > 0) ? $ipSus[0] : $ip;

        return array(
            'ip' => $ip,
            'suspected' => $ipSus,
            'network' => $ipAll,
        );
    }
}