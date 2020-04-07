<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Rest;


use Exception;
use MODX\Revolution\modX;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * A MODX-powered REST service class for dynamic REST API applications. Uses controller classes to handle routing
 * requests. Also supports xml/json/qs formats, and path/to/object/id routes.
 *
 * @package MODX\Revolution\Rest
 */
class modRestService
{
    /** @var modX $modx A reference to the modX instance */
    public $modx;
    /** @var array $config The configuration array */
    public $config = [];
    /** @var modRestServiceRequest $request The REST request object for this service */
    public $request;
    /** @var modRestServiceResponse $response The REST response object for this service */
    public $response;
    /** @var int|string $requestPrimaryKey The primary key requested on the object/id route */
    public $requestPrimaryKey;

    /**
     * @param modX  $modx
     * @param array $config
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $this->config = array_merge([
            'basePath' => $this->modx->getOption('base_path', null, MODX_BASE_PATH),
            'collectionResultsKey' => 'results',
            'collectionTotalKey' => 'total',
            'controllerClassPrefix' => 'modRestController',
            'controllerClassSeparator' => '',
            'defaultAction' => 'index',
            'defaultResponseFormat' => 'json',
            'defaultFailureStatusCode' => 200,
            'defaultSuccessStatusCode' => 200,
            'errorMessageSeparator' => ' ',
            'exitOnResponse' => true,
            'propertyLimit' => 'limit',
            'propertyOffset' => 'start',
            'propertySearch' => 'search',
            'propertySort' => 'sort',
            'propertySortDir' => 'dir',
            'requestParameter' => '_rest',
            'responseErrorsKey' => 'errors',
            'responseMessageKey' => 'message',
            'responseObjectKey' => 'object',
            'responseSuccessKey' => 'success',
            'trimParameters' => false,
            'xmlRootNode' => 'response',
            'sanitize' => false,
        ], $config);
        $this->modx->getService('lexicon', 'modLexicon');
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('rest');
        }
    }

    /**
     * Get a configuration option for this service
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }


    /**
     * Check permissions for the request.
     *
     * @return boolean
     */
    public function checkPermissions()
    {
        return true;
    }

    /**
     * Prepare the request object, setting the method, headers, format and parameters
     */
    public function prepare()
    {
        $requestParameter = $this->getOption('requestParameter', '_rest');
        $this->request = new modRestServiceRequest($this);
        $this->request->setAction();
        $this->request->setFormat($this->getOption('defaultResponseFormat', 'json'));
        $this->request->checkForSuffix();
        unset($_GET[$requestParameter]);
        $this->request->setMethod();
        $this->request->setHeaders();
        $this->request->setRequestParameters();
    }

    /**
     * Process the request, creating the controller and response objects, and then sending the processed
     * response back to the client. The controller is determined by the path passed to the request parameter, and
     * the controller's method is determined by the HTTP request method sent.
     */
    public function process()
    {
        try {
            $controllerName = $this->getController();
            if (null == $controllerName) {
                throw new Exception('Method not allowed', 405);
            }
            /** @var modRestController $controller */
            $controller = new ReflectionClass($controllerName);
            if (!$controller->isInstantiable()) {
                throw new Exception('Bad Request', 400);
            }
            $controller->properties = $this->request->parameters;
            $controller->headers = $this->request->headers;
            try {
                /** @var ReflectionMethod $method */
                $method = $controller->getMethod($this->request->method);
            } catch (ReflectionException $e) {
                throw new Exception('Unsupported HTTP method ' . $this->request->method, 405);
            }
            if (!$method->isStatic()) {
                $controller = $controller->newInstance($this->modx, $this->request, $this->config);
                $controller->setProperties($this->request->parameters);
                $controller->setHeaders($this->request->headers);
                if ($controller->isProtected() && $this->request->method != 'options') {
                    if (!$controller->verifyAuthentication()) {
                        throw new Exception('Unauthorized', 401);
                    }
                }
                if (!empty($this->requestPrimaryKey)) {
                    $controller->setProperty($controller->primaryKeyField, $this->requestPrimaryKey);
                }
                $controller->initialize();
                $method->invoke($controller);
                $this->response = new modRestServiceResponse($this, $controller->getResponse(),
                    $controller->getResponseStatus());
            } else {
                throw new Exception('Static methods not supported in Controllers', 500);
            }
            if (empty($this->response)) {
                throw new Exception('Method not allowed', 405);
            }
        } catch (Exception $error) {
            $this->response = new modRestServiceResponse($this, [
                'success' => false,
                'message' => $error->getMessage(),
                'object' => [],
                'code' => $error->getCode(),
            ], $error->getCode());
        }
        $contentType = $this->getResponseContentType($this->request->format);
        $this->response->setContentType($contentType);
        $this->response->prepare();

        return $this->response->send();
    }

    /**
     * Get the Response content type based on the format passed
     *
     * @param string $format
     *
     * @return string
     */
    public function getResponseContentType($format = 'json')
    {
        $supportedFormats = $this->getOption('supportedFormats', 'xml,json,qs');
        $supportedFormats = explode(',', $supportedFormats);
        if (!in_array($format, $supportedFormats)) {
            $contentType = $this->getOption('defaultResponseFormat', 'json');
        } else {
            $contentType = $format;
        }

        return trim($contentType);
    }

    /**
     * Get the correct controller path for the class
     *
     * @return string
     */
    protected function getController()
    {
        $expectedFile = trim($this->request->action, '/');
        $basePath = $this->getOption('basePath');
        $controllerClassPrefix = $this->getOption('controllerClassPrefix', 'modController');
        $controllerClassSeparator = $this->getOption('controllerClassSeparator', '_');
        $controllerClassFilePostfix = $this->getOption('controllerClassFilePostfix', '.php');

        /* handle [object]/[id] pathing */
        $expectedArray = explode('/', $expectedFile);
        if (empty($expectedArray)) {
            $expectedArray = [rtrim($expectedFile, '/') . '/'];
        }
        $id = array_pop($expectedArray);
        if (!file_exists($basePath . $expectedFile . $controllerClassFilePostfix) && !empty($id)) {
            $expectedFile = implode('/', $expectedArray);
            if (empty($expectedFile)) {
                $expectedFile = $id;
                $id = null;
            }
            $this->requestPrimaryKey = $id;
        }

        foreach ($this->iterateDirectories($basePath . '/*' . $controllerClassFilePostfix,
            GLOB_NOSORT) as $controller) {
            $controller = $basePath != '/' ? str_replace($basePath, '', $controller) : $controller;
            $controller = trim($controller, '/');
            $controllerFile = str_replace([$controllerClassFilePostfix], [''], $controller);
            $controllerClass = str_replace(['/', $controllerClassFilePostfix], [$controllerClassSeparator, ''],
                $controller);
            if (strnatcasecmp($expectedFile, $controllerFile) == 0) {
                require_once $basePath . $controller;

                return $controllerClassPrefix . $controllerClassSeparator . $controllerClass;
            }
        }
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Could not find expected controller: ' . $expectedFile);

        return null;
    }

    /**
     * Iterate across directories looking for files based on a pattern
     *
     * @param string $pattern
     * @param int    $flags
     *
     * @return array
     */
    public function iterateDirectories($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        $dirs = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

        if ($dirs) {
            foreach ($dirs as $dir) {
                $files = array_merge($files, $this->iterateDirectories($dir . '/' . basename($pattern), $flags));
            }
        }

        return $files;
    }

    /**
     * Send either to the unauthorized page or exit out with a 401
     *
     * @param bool $exit
     */
    public function sendUnauthorized($exit = true)
    {
        if (!$exit) {
            $this->modx->sendUnauthorizedPage();
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
            @session_write_close();
            exit(0);
        }
    }
}
