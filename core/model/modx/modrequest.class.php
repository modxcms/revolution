<?php
/*
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
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
 */
/**
 * Encapsulates the interaction of MODx with an HTTP request.
 *
 * This class represents the functional portion of the MODx {@link
 * http://www.martinfowler.com/eaaCatalog/frontController.html front-
 * controller}, and is responsible for sanitizing, interpretting, and
 * dispatching a web request to the appropriate MODx {@link modResource
 * Web Resource}.
 *
 * @package modx
 */
class modRequest {
    public $modx = null;
    public $method = null;
    public $parameters = null;
    public $headers = null;

    function __construct(modX &$modx) {
        $this->modx = & $modx;
        $this->parameters['GET'] =& $_GET;
        $this->parameters['POST'] =& $_POST;
        $this->parameters['COOKIE'] =& $_COOKIE;
        $this->parameters['REQUEST'] =& $_REQUEST;
    }

    /**
     * The primary MODx request handler (a.k.a. controller).
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
                $this->modx->resourceIdentifier = $this->modx->getOption('site_unavailable_page');
            }
        } else {
            $this->checkPublishStatus();
            $this->modx->resourceMethod = $this->getResourceMethod();
            $this->modx->resourceIdentifier = $this->getResourceIdentifier($this->modx->resourceMethod);
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
            }
            $this->modx->resourceMethod = 'id';
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
     * Prepares the MODx response to a web request that is being handled.
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
        if (isset ($_REQUEST[$this->modx->getOption('request_param_alias', null, 'q')]))
            $method = "alias";
        elseif (isset ($_REQUEST[$this->modx->getOption('request_param_id', null, 'id')])) {
            $method = "id";
        }
        return $method;
    }

    /**
     * Gets a requested resource and all required data.
     *
     * @param string $method The method, 'id', or 'alias', by which to perform
     * the resource lookup.
     * @param string|integer $identifier The identifier with which to search.
     * @return modResource The requested modResource instance or request
     * is forwarded to the error page, or unauthorized page.
     */
    public function getResource($method, $identifier) {
        $resource = null;
        if ($method == 'alias') {
            $resourceId = $this->modx->aliasMap[$identifier];
        } else {
            $resourceId = $identifier;
        }

        if (!intval($resourceId)) {
            $this->modx->sendErrorPage();
        }
        $fromCache = false;
        $cacheKey = $this->modx->context->get('key') . "/resources/{$resourceId}";
        if ($cachedResource = $this->modx->cacheManager->get($cacheKey)) {
            $resource = $this->modx->newObject($cachedResource['resourceClass']);
            if ($resource) {
                $resource->fromArray($cachedResource['resource'], '', true, true, true);
                $this->modx->documentObject = & $resource->_fields;
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
                if (isset($cachedResource['elementCache'])) $this->modx->elementCache = $cachedResource['elementCache'];
                if (isset($resource->_jscripts)) $this->modx->jscripts = array_merge($this->modx->jscripts, $resource->_jscripts);
                if (isset($resource->_sjscripts)) $this->modx->sjscripts = array_merge($this->modx->sjscripts, $resource->_sjscripts);
                if (isset($resource->_loadedjscripts)) $this->modx->loadedjscripts = array_merge($this->modx->loadedjscripts, $resource->_loadedjscripts);
                $fromCache = true;
            }
        }
        $this->modx->resourceGenerated = (boolean) !$fromCache;
        if (!$fromCache || !is_object($resource)) {
            $criteria = array('id' => $resourceId, 'deleted' => '0');
            if (!$this->modx->hasPermission('view_unpublished')) $criteria['published']= 1;
            if ($resource = $this->modx->getObject('modResource', $criteria)) {
                if ($resource instanceof modResource) {
                    if ($resource->get('context_key') !== $this->modx->context->get('key')) {
                        if (!$this->modx->getCount('modContextResource', array($this->modx->context->get('key'), $resourceId))) {
                            return null;
                        }
                    }
                    if (!$resource->checkPolicy('view')) {
                        $this->modx->sendUnauthorizedPage();
                    }
                    $this->modx->documentObject = & $resource->_fields;
                    if ($tvs = $resource->getMany('TemplateVars', 'all')) {
                        foreach ($tvs as $tv) {
                            $this->modx->documentObject[$tv->get('name')] = array (
                                $tv->get('name'),
                                $tv->getValue($resource->get('id')),
                                $tv->get('display'),
                                $tv->get('display_params'),
                                $tv->get('type'),
                            );
                        }
                    }
                }
            }
        } elseif ($fromCache && $resource instanceof modResource && !$resource->get('deleted')) {
            if ($resource->checkPolicy('load') && ($resource->get('published') || $this->modx->hasPermission('view_unpublished'))) {
                if ($resource->get('context_key') !== $this->modx->context->get('key')) {
                    if (!$this->modx->getCount('modContextResource', array($this->modx->context->get('key'), $resourceId))) {
                        return null;
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
                $rAlias = $this->modx->getOption('request_param_alias',null,'q');
                $identifier = isset ($_REQUEST[$rAlias]) ? $_REQUEST[$rAlias] : $identifier;
                break;
            case 'id' :
                $rId = $this->modx->getOption('request_param_id',null,'id');
                $identifier = isset ($_REQUEST[$rId]) ? $_REQUEST[$rId] : $identifier;
                break;
            default :
                $identifier = $this->modx->getOption('site_start',null,1);
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
            $identifier = $this->modx->getOption('site_start',null,1);
            $this->modx->resourceMethod = 'id';
        }
        elseif ($this->modx->getOption('friendly_urls',null,false)) {
            $containerSuffix = trim($this->modx->getOption('container_suffix',null,''));
            if (!isset ($this->modx->aliasMap[$identifier])) {
                if (!empty ($containerSuffix)) {
                    $suffixPos = strpos($identifier, $containerSuffix);
                    $suffixLen = strlen($containerSuffix);
                    $identifierLen = strlen($identifier);
                    if (substr($identifier, $identifierLen - $suffixLen) === $containerSuffix) {
                        $identifier = substr($identifier, 0, $identifierLen - $suffixLen);
                    }
                    elseif (isset ($this->modx->aliasMap["{$identifier}{$containerSuffix}"])) {
                        $identifier = "{$identifier}{$containerSuffix}";
                    }
                    if (isset ($this->modx->aliasMap[$identifier])) {
                        $url = $this->modx->makeUrl($this->modx->aliasMap[$identifier]);
                        $this->modx->sendRedirect($url);
                    }
                    $this->modx->resourceMethod = 'alias';
                }
            }
            elseif ($this->modx->getOption('site_start',null,1) == $this->modx->aliasMap[$identifier]) {
                $this->modx->sendRedirect($this->modx->getOption('site_url'));
            } else {
                $this->modx->resourceMethod = 'alias';
            }
        } else {
            $this->modx->resourceMethod = 'id';
        }
        return $identifier;
    }

    /**
     * Harden GPC variables by removing any MODx tags, Javascript, or entities.
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
        $rAlias = $this->modx->getOption('request_param_alias',null,'q');
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
     * Retrieve's a preserved $_REQUEST from $_SESSION.
     *
     * @param string $key A key to identify a specific $_REQUEST; default is 'referrer'.
     */
    public function retrieveRequest($key = 'referrer') {
        $request = null;
        if (isset ($_SESSION['modx.request.' . $key])) {
            $request = $_SESSION['modx.request.' . $key];
        }
        return $request;
    }

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
     * @todo refactor checkPublishStatus...offload to cachemanager?
     */
    public function checkPublishStatus() {
        $cacheRefreshTime= 0;
        if (file_exists($this->modx->getOption(xPDO::OPT_CACHE_PATH) . "sitePublishing.idx.php"))
            include ($this->modx->getOption(xPDO::OPT_CACHE_PATH) . "sitePublishing.idx.php");
        $timeNow= time() + $this->modx->getOption('server_offset_time',null,0);
        if ($cacheRefreshTime != 0 && $cacheRefreshTime <= strtotime($timeNow)) {
            /* FIXME: want to find a better way to handle this publishing check without mass updates to the database! */
            $tblResource= $this->modx->getTableName('modResource');
            if (!$result= $this->modx->exec("UPDATE {$tblResource} SET published=1,publishedon={$timeNow} WHERE pub_date < {$timeNow} AND pub_date > 0")) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error while refreshing resource publishing data: ' . print_r($this->modx->errorInfo(), true));
            }
            if (!$result= $this->modx->exec("UPDATE $tblResource SET published=0,publishedon={$timeNow} WHERE unpub_date < {$timeNow} AND unpub_date IS NOT NULL AND unpub_date > 0")) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error while refreshing resource unpublishing data: ' . print_r($this->modx->errorInfo(), true));
            }
            if ($this->modx->getCacheManager()) {
                $this->modx->cacheManager->clearCache();
            }
            $timesArr= array ();
            $sql= "SELECT MIN(pub_date) AS minpub FROM $tblResource WHERE pub_date>$timeNow AND pub_date IS NOT NULL";
            if (!$result= $this->modx->query($sql)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Failed to find publishing timestamps\n" . $sql);
            } else {
                $result= $result->fetchAll(PDO::FETCH_ASSOC);
                $minpub= $result[0]['minpub'];
                if ($minpub != null) {
                    $timesArr[]= $minpub;
                }
            }
            $sql= "SELECT MIN(unpub_date) AS minunpub FROM $tblResource WHERE unpub_date>$timeNow AND unpub_date IS NOT NULL";
            if (!$result= $this->modx->query($sql)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Failed to find publishing timestamps\n" . $sql);
            } else {
                $result= $result->fetchAll(PDO::FETCH_ASSOC);
                $minunpub= $result[0]['minunpub'];
                if ($minunpub != null) {
                    $timesArr[]= $minunpub;
                }
            }
            if (count($timesArr) > 0) {
                $nextevent= min($timesArr);
            } else {
                $nextevent= 0;
            }
            $fp= @ fopen($this->modx->getOption(xPDO::OPT_CACHE_PATH) . "sitePublishing.idx.php", "wb");
            if ($fp) {
                @ flock($fp, LOCK_EX);
                @ fwrite($fp, "<?php \$cacheRefreshTime=$nextevent; ?>");
                @ flock($fp, LOCK_UN);
                @ fclose($fp);
            }
        }
    }

    public function getAllActionIDs($namespace = '') {
        $c = array();
        if (!empty($namespace)) $c['namespace'] = $namespace;
        $actions = $this->modx->getCollection('modAction',$c);

        $actionList = array();
        foreach ($actions as $action) {
            $actionList[$action->get('controller')] = $action->get('id');
        }
        return $actionList;
    }

    public function getActionIDs(array $actions = array(), $namespace = 'core') {
        $as = array();
        foreach ($actions as $action) {
            $act = $this->modx->getObject('modAction',array(
                'namespace' => $namespace,
                'controller' => $action,
            ));
            if ($act == null) {
                $as[$action] = 0;
            } else {
                $as[$action] = $act->get('id');
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
                $methodIdentifier = $this->modx->getOption('request_param_' . $method, null, $method);
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
}