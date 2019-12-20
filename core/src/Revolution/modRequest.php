<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution;

use MODX\Revolution\Error\modError;
use MODX\Revolution\Registry\modFileRegister;
use MODX\Revolution\Registry\modRegistry;
use xPDO\Cache\xPDOCacheManager;
use xPDO\xPDO;

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
class modRequest
{
    /**
     * A reference to the modX object
     *
     * @var modX $modx
     */
    public $modx = null;
    /**
     * The current request method
     *
     * @var string $method
     */
    public $method = null;
    /**
     * The parameters sent in the request
     *
     * @var array $parameters
     */
    public $parameters = null;
    /**
     * The HTTP headers sent in the request
     *
     * @var array $headers
     */
    public $headers = null;

    /**
     * @param modX $modx A reference to the modX object
     */
    public function __construct(modX $modx)
    {
        $this->modx = &$modx;
        $this->parameters['GET'] =& $_GET;
        $this->parameters['POST'] =& $_POST;
        $this->parameters['COOKIE'] =& $_COOKIE;
        $this->parameters['REQUEST'] =& $_REQUEST;
    }

    /**
     * The primary MODX request handler (a.k.a. controller).
     */
    public function handleRequest()
    {
        $this->loadErrorHandler();

        // If enabled, send the X-Powered-By header to identify this site as running MODX, per discussion in #12882
        if ($this->modx->getOption('send_poweredby_header', null, true)) {
            $version = $this->modx->getVersionData();
            header("X-Powered-By: MODX {$version['code_name']}");
        }

        $this->sanitizeRequest();
        $this->modx->invokeEvent('OnHandleRequest');
        if (!$this->modx->checkSiteStatus()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');
            if (!$this->modx->getOption('site_unavailable_page', null, 1)) {
                $this->modx->resource = $this->modx->newObject(modDocument::class);
                $this->modx->resource->template = 0;
                $this->modx->resource->content = $this->modx->getOption('site_unavailable_message');
            } else {
                $this->modx->resourceMethod = "id";
                $this->modx->resourceIdentifier = $this->modx->getOption('site_unavailable_page', null, 1);
            }
        } else {
            $this->checkPublishStatus();
            $this->modx->resourceMethod = $this->getResourceMethod();
            $this->modx->resourceIdentifier = $this->getResourceIdentifier($this->modx->resourceMethod);
            if ($this->modx->resourceMethod == 'id' && $this->modx->getOption('friendly_urls', null,
                    false) && $this->modx->getOption('request_method_strict', null, false)) {
                $uri = $this->modx->context->getResourceURI($this->modx->resourceIdentifier);
                if (!empty($uri)) {
                    if ((integer)$this->modx->resourceIdentifier === (integer)$this->modx->getOption('site_start', null,
                            1)) {
                        $url = $this->modx->getOption('site_url', null, MODX_SITE_URL);
                    } else {
                        $url = $this->modx->getOption('site_url', null, MODX_SITE_URL) . $uri;
                    }
                    $this->modx->sendRedirect($url,
                        ['responseCode' => $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently']);
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
            $found = $this->modx->findResource($this->modx->resourceIdentifier);
            if ($found) {
                $this->modx->resourceIdentifier = $found;
                $this->modx->resourceMethod = 'id';
            } else {
                $this->modx->sendErrorPage();
            }
        }
        $this->modx->beforeRequest();
        $this->modx->invokeEvent("OnWebPageInit");

        if (!is_object($this->modx->resource)) {
            if (!$this->modx->resource = $this->getResource($this->modx->resourceMethod,
                $this->modx->resourceIdentifier)) {
                $this->modx->sendErrorPage();
            }
        }

        $this->prepareResponse();
    }

    /**
     * Prepares the MODX response to a web request that is being handled.
     *
     * @param array $options An array of options
     */
    public function prepareResponse(array $options = [])
    {
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
    public function getResourceMethod()
    {
        $method = '';
        $hasId = isset($_REQUEST[$this->modx->getOption('request_param_id', null, 'id')]);
        $hasAlias = isset($_REQUEST[$this->modx->getOption('request_param_alias', null, 'q')]);
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
     * @param string         $method     The method, 'id', or 'alias', by which to perform
     *                                   the resource lookup.
     * @param string|integer $identifier The identifier with which to search.
     * @param array          $options    An array of options for the resource fetching
     *
     * @return modResource The requested modResource instance or request
     * is forwarded to the error page, or unauthorized page.
     */
    public function getResource($method, $identifier, array $options = [])
    {
        $resource = null;
        if ($method == 'alias') {
            $resourceId = $this->modx->findResource($identifier);
        } else {
            $resourceId = $identifier;
        }

        if (!is_numeric($resourceId)) {
            $this->modx->sendErrorPage();
        }
        $isForward = array_key_exists('forward', $options) && !empty($options['forward']);
        $fromCache = false;
        $cacheKey = $this->modx->context->get('key') . "/resources/{$resourceId}";
        $cachedResource = $this->modx->cacheManager->get($cacheKey, [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_resource_key', null, 'resource'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_resource_handler', null,
                $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$this->modx->getOption('cache_resource_format', null,
                $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);
        if (is_array($cachedResource) && array_key_exists('resource',
                $cachedResource) && is_array($cachedResource['resource'])) {
            /** @var modResource $resource */
            $resource = $this->modx->newObject($cachedResource['resourceClass']);
            if ($resource) {
                $resource->fromArray($cachedResource['resource'], '', true, true, true);
                $resource->_content = $cachedResource['resource']['_content'];
                $resource->_isForward = $isForward;
                if (isset($cachedResource['contentType'])) {
                    $contentType = $this->modx->newObject(modContentType::class);
                    $contentType->fromArray($cachedResource['contentType'], '', true, true, true);
                    $resource->addOne($contentType, 'ContentType');
                }
                if (isset($cachedResource['resourceGroups'])) {
                    $rGroups = [];
                    foreach ($cachedResource['resourceGroups'] as $rGroupKey => $rGroup) {
                        $rGroups[$rGroupKey] = $this->modx->newObject(modResourceGroupResource::class, $rGroup);
                    }
                    $resource->addMany($rGroups);
                }
                if (isset($cachedResource['policyCache'])) {
                    $resource->setPolicies([$this->modx->context->get('key') => $cachedResource['policyCache']]);
                }
                if (isset($cachedResource['elementCache'])) {
                    $this->modx->elementCache = $cachedResource['elementCache'];
                }
                if (isset($cachedResource['sourceCache'])) {
                    $this->modx->sourceCache = $cachedResource['sourceCache'];
                }
                if ($resource->get('_jscripts')) {
                    $this->modx->jscripts = $this->modx->jscripts + $resource->get('_jscripts');
                }
                if ($resource->get('_sjscripts')) {
                    $this->modx->sjscripts = $this->modx->sjscripts + $resource->get('_sjscripts');
                }
                if ($resource->get('_loadedjscripts')) {
                    $this->modx->loadedjscripts = array_merge($this->modx->loadedjscripts,
                        $resource->get('_loadedjscripts'));
                }
                $isForward = $resource->_isForward;
                $resource->setProcessed(true);
                $fromCache = true;
            }
        }
        if (!$fromCache || !is_object($resource)) {
            $criteria = $this->modx->newQuery(modResource::class);
            $criteria->select([$this->modx->escape('modResource') . '.*']);
            $criteria->where(['id' => $resourceId, 'deleted' => '0']);
            if (!$this->modx->hasPermission('view_unpublished') || $this->modx->getSessionState() !== modX::SESSION_STATE_INITIALIZED) {
                $criteria->where(['published' => 1]);
            }
            if ($resource = $this->modx->getObject(modResource::class, $criteria)) {
                if ($resource instanceof modResource) {
                    if ($resource->get('context_key') !== $this->modx->context->get('key')) {
                        if (!$isForward || ($isForward && !$this->modx->getOption('allow_forward_across_contexts',
                                    $options, false))) {
                            if (!$this->modx->getCount(modContextResource::class,
                                [$this->modx->context->get('key'), $resourceId])) {
                                return null;
                            }
                        }
                    }
                    $resource->_isForward = $isForward;
                    if (!$resource->checkPolicy('view')) {
                        $this->modx->sendUnauthorizedPage();
                    }
                    if ($tvs = $resource->getMany('TemplateVars', 'all')) {
                        /** @var modTemplateVar $tv */
                        foreach ($tvs as $tv) {
                            $resource->set($tv->get('name'), [
                                $tv->get('name'),
                                $tv->getValue($resource->get('id')),
                                $tv->get('display'),
                                $tv->get('display_params'),
                                $tv->get('type'),
                            ]);
                        }
                    }
                    $this->modx->resourceGenerated = true;
                }
            }
        } elseif ($fromCache && $resource instanceof modResource && !$resource->get('deleted')) {
            if ($resource->checkPolicy('load') && ($resource->get('published') || ($this->modx->getSessionState() === modX::SESSION_STATE_INITIALIZED && $this->modx->hasPermission('view_unpublished')))) {
                if ($resource->get('context_key') !== $this->modx->context->get('key')) {
                    if (!$isForward || ($isForward && !$this->modx->getOption('allow_forward_across_contexts', $options,
                                false))) {
                        if (!$this->modx->getCount(modContextResource::class,
                            [$this->modx->context->get('key'), $resourceId])) {
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
            $this->modx->invokeEvent('OnLoadWebPageCache', [
                'resource' => &$resource,
            ]);
        }

        return $resource;
    }

    /**
     * Gets the idetifier used to request a resource.
     *
     * @param string $method 'alias' or 'id'.
     *
     * @return string The identifier for the requested resource.
     */
    public function getResourceIdentifier($method)
    {
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
     *
     * @return string|integer The cleansed identifier.
     */
    public function _cleanResourceIdentifier($identifier)
    {
        if (empty ($identifier)) {
            if ($this->modx->getOption('base_url', null, MODX_BASE_URL) !== strtok($_SERVER["REQUEST_URI"], '?')) {
                $this->modx->sendRedirect($this->modx->getOption('site_url', null, MODX_SITE_URL),
                    ['responseCode' => $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently']);
            }
            $identifier = $this->modx->getOption('site_start', null, 1);
            $this->modx->resourceMethod = 'id';
        } elseif ($this->modx->getOption('friendly_urls', null, false) && $this->modx->resourceMethod == 'alias') {
            $containerSuffix = trim($this->modx->getOption('container_suffix', null, ''));
            $found = $this->modx->findResource($identifier);
            if ($found === false && !empty ($containerSuffix)) {
                $suffixLen = strlen($containerSuffix);
                $identifierLen = strlen($identifier);
                if (substr($identifier, $identifierLen - $suffixLen) === $containerSuffix) {
                    $identifier = substr($identifier, 0, $identifierLen - $suffixLen);
                    $found = $this->modx->findResource($identifier);
                } else {
                    $identifier = "{$identifier}{$containerSuffix}";
                    $found = $this->modx->findResource($identifier);
                }
                if ($found) {
                    $parameters = $this->getParameters();
                    unset($parameters[$this->modx->getOption('request_param_alias')]);
                    $url = $this->modx->makeUrl($found, $this->modx->context->get('key'), $parameters, 'full');
                    $this->modx->sendRedirect($url,
                        ['responseCode' => $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently']);
                }
                $this->modx->resourceMethod = 'alias';
            } elseif ((integer)$this->modx->getOption('site_start', null, 1) === $found) {
                $parameters = $this->getParameters();
                unset($parameters[$this->modx->getOption('request_param_alias')]);
                $url = $this->modx->makeUrl($this->modx->getOption('site_start', null, 1),
                    $this->modx->context->get('key'), $parameters, 'full');
                $this->modx->sendRedirect($url,
                    ['responseCode' => $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently']);
            } else {
                if ($this->modx->getOption('friendly_urls_strict', null, false)) {
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $qsPos = strpos($requestUri, '?');
                    if ($qsPos !== false) {
                        $requestUri = substr($requestUri, 0, $qsPos);
                    }
                    $fullId = $this->modx->getOption('base_url', null, MODX_BASE_URL) . $identifier;
                    $requestUri = urldecode($requestUri);
                    if ($fullId !== $requestUri && strpos($requestUri, $fullId) !== 0) {
                        $parameters = $this->getParameters();
                        unset($parameters[$this->modx->getOption('request_param_alias')]);
                        $url = $this->modx->makeUrl($found, $this->modx->context->get('key'), $parameters, 'full');
                        $this->modx->sendRedirect($url,
                            ['responseCode' => $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently']);
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
    public function sanitizeRequest()
    {
        $modxtags = array_values($this->modx->sanitizePatterns);

        $depth = (int)ini_get('max_input_nesting_level');
        $depth = ($depth <= 0) ? 99 : $depth + 1;

        modX:: sanitize($_GET, $modxtags, $depth);
        if ($this->modx->getOption('allow_tags_in_post', null, true)) {
            modX:: sanitize($_POST);
        } else {
            modX:: sanitize($_POST, $modxtags, $depth);
        }
        modX:: sanitize($_COOKIE, $modxtags, $depth);
        modX:: sanitize($_REQUEST, $modxtags, $depth);
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
    public function loadErrorHandler($class = modError::class)
    {
        if (class_exists($class)) {
            $this->modx->error = new $class($this->modx);
        } else {
            $this->modx->log(modX::LOG_LEVEL_FATAL, 'Error handling class could not be loaded: ' . $class);
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
     * <li>clear: set flag to clear register before logging new messages into it  (optional)</li>
     * </ul>
     *
     * @param array $options An array containing all the options required to
     *                       initiate and configure logging to a modRegister instance.
     */
    public function registerLogging(array $options = [])
    {
        if (isset($options['register']) && isset($options['topic'])) {
            if ($this->modx->getService('registry', modRegistry::class)) {
                $register_class = isset($options['register_class']) ? $options['register_class'] : modFileRegister::class;
                $register = $this->modx->registry->getRegister($options['register'], $register_class);
                if ($register) {
                    $level = isset($options['log_level']) ? $options['log_level'] : modX::LOG_LEVEL_INFO;
                    $clear = (!empty($options['clear']) && $options['clear'] !== 'false');
                    $this->modx->registry->setLogging($register, $options['topic'], $level, $clear);
                }
            }
        }
    }

    /**
     * Preserves the $_REQUEST superglobal to the $_SESSION.
     *
     * @param string $key A key to save the $_REQUEST as; default is 'referrer'.
     */
    public function preserveRequest($key = 'referrer')
    {
        if (isset ($_SESSION)) {
            $_SESSION['modx.request.' . $key] = $_REQUEST;
        }
    }

    /**
     * Retrieve a preserved $_REQUEST from $_SESSION.
     *
     * @param string $key A key to identify a specific $_REQUEST; default is 'referrer'.
     *
     * @return mixed
     */
    public function retrieveRequest($key = 'referrer')
    {
        $request = null;
        if (isset ($_SESSION['modx.request.' . $key])) {
            $request = $_SESSION['modx.request.' . $key];
        }

        return $request;
    }

    /**
     * Return the HTTP headers sent through the request
     *
     * @param boolean $ucKeys if true, upper-case all keys for the headers
     *
     * @return array
     */
    public function getHeaders($ucKeys = false)
    {
        if (!isset($this->headers)) {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr(strtoupper($name), 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-',
                        ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
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
     * Checks the current status of timed publishing events and automatically (un)publishes resources if needed.
     */
    public function checkPublishStatus()
    {
        $partKey = $this->modx->getOption('cache_auto_publish_key', null, 'auto_publish');
        $partHandler = $this->modx->getOption('cache_auto_publish_handler', null,
            $this->modx->getOption(xPDO::OPT_CACHE_HANDLER));
        $partOptions = [xPDO::OPT_CACHE_KEY => $partKey, xPDO::OPT_CACHE_HANDLER => $partHandler];

        $cacheRefreshTime = (integer)$this->modx->cacheManager->get('auto_publish', [
            xPDO::OPT_CACHE_KEY => $partKey,
            xPDO::OPT_CACHE_HANDLER => $partHandler,
        ]);
        if ($cacheRefreshTime > 0) {
            $timeNow = time();
            if ($cacheRefreshTime <= $timeNow) {
                $results = $this->modx->cacheManager->autoPublish($partOptions);

                // Get the affected contexts
                $contexts = [];
                if (array_key_exists('published_resources', $results) && is_array($results['published_resources'])) {
                    foreach ($results['published_resources'] as $published) {
                        $contexts[] = $published['context_key'];
                    }
                }
                if (array_key_exists('unpublished_resources',
                        $results) && is_array($results['unpublished_resources'])) {
                    foreach ($results['unpublished_resources'] as $unpublished) {
                        $contexts[] = $unpublished['context_key'];
                    }
                }
                $contexts = array_unique($contexts);

                // If at least one context was affected, refresh the context_settings (which contains the alias map etc)
                // and the resource cache for those contexts.
                if (count($contexts) > 0) {
                    $this->modx->cacheManager->refresh([
                        'db' => [],
                        'context_settings' => ['contexts' => $contexts],
                        'resource' => ['contexts' => $contexts],
                    ]);
                }
            }
        }
    }

    /**
     * Get a GPC/REQUEST variable value or an array of values from the request.
     *
     * @param string|array $keys A key or array of keys to retrieve from the GPC variable. An empty
     *                           array means get all keys of the variable.
     * @param string       $type The type of GPC variable, GET by default (GET, POST, COOKIE or REQUEST).
     *
     * @return mixed
     */
    public function getParameters($keys = [], $type = 'GET')
    {
        $value = null;
        if (!is_string($type) || !in_array($type, ['GET', 'POST', 'COOKIE', 'REQUEST'])) {
            $type = 'GET';
        }
        if (is_array($keys)) {
            $value = [];
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
                        if ($keyValue !== null) {
                            $value[$key] = $keyValue;
                        }
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
    public function getClientIp()
    {
        $ip = '';
        $ipAll = []; // networks IP
        $ipSus = []; // suspected IP

        $serverVariables = [
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
        ];

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
                $ipAll = array_merge($ipAll, $tmp);
            }
        }

        $ipSus = array_unique($ipSus);
        $ipAll = array_unique($ipAll);
        $ip = (sizeof($ipSus) > 0) ? $ipSus[0] : $ip;

        return [
            'ip' => $ip,
            'suspected' => $ipSus,
            'network' => $ipAll,
        ];
    }
}
