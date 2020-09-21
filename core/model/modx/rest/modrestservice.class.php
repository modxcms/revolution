<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 */

require_once realpath(dirname(__FILE__).'/modrestcontroller.class.php');
/**
 * A MODX-powered REST service class for dynamic REST API applications. Uses controller classes to handle routing
 * requests. Also supports xml/json/qs formats, and path/to/object/id routes.
 *
 * @package modx
 * @subpackage rest
 */
class modRestService {
    /** @var modX $modx A reference to the modX instance */
    public $modx;
    /** @var array $config The configuration array */
    public $config = array();
    /** @var modRestServiceRequest $request The REST request object for this service */
	public $request;
	/** @var modRestServiceResponse $response The REST response object for this service */
	public $response;
	/** @var int|string $requestPrimaryKey The primary key requested on the object/id route */
    public $requestPrimaryKey;

	/**
     * @param modX $modx
     * @param array $config
	 */
	public function __construct(modX &$modx,array $config = array()) {
		$this->modx =& $modx;
		$this->config = array_merge(array(
            'basePath' => $this->modx->getOption('base_path',null,MODX_BASE_PATH),
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
            'xmlDisableEntityLoader' => true,
		),$config);
		$this->modx->getService('lexicon','modLexicon');
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('rest');
        }
	}

    /**
     * Get a configuration option for this service
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key,$default = null) {
        return array_key_exists($key,$this->config) ? $this->config[$key] : $default;
    }


    /**
     * Check permissions for the request.
     *
     * @return boolean
     */
	public function checkPermissions() {
        return true;
	}

    /**
     * Prepare the request object, setting the method, headers, format and parameters
     */
	public function prepare() {
	    $requestParameter = $this->getOption('requestParameter','_rest');
	    $this->request = new modRestServiceRequest($this);
	    $this->request->setAction();
	    $this->request->setFormat($this->getOption('defaultResponseFormat','json'));
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
	public function process() {
		try	{
			$controllerName = $this->getController();
			if(null == $controllerName) {
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
				$controller = $controller->newInstance($this->modx,$this->request,$this->config);
			    $controller->setProperties($this->request->parameters);
			    $controller->setHeaders($this->request->headers);
			    if ($controller->isProtected() && $this->request->method != 'options') {
                    if (!$controller->verifyAuthentication()) {
                        throw new Exception('Unauthorized', 401);
                    }
                }
			    if (!empty($this->requestPrimaryKey)) {
			        $controller->setProperty($controller->primaryKeyField,$this->requestPrimaryKey);
                }
				$controller->initialize();
				$method->invoke($controller);
				$this->response = new modRestServiceResponse($this,$controller->getResponse(),$controller->getResponseStatus());
			} else {
				throw new Exception('Static methods not supported in Controllers', 500);
			}
			if (empty($this->response)) {
				throw new Exception('Method not allowed', 405);
			}
		} catch (Exception $error)	{
		    $this->response = new modRestServiceResponse($this,array(
			    'success' => false,
                'message' => $error->getMessage(),
                'object' => array(),
                'code' => $error->getCode(),
            ),$error->getCode());
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
     * @return string
     */
    public function getResponseContentType($format = 'json') {
        $supportedFormats = $this->getOption('supportedFormats','xml,json,qs');
        $supportedFormats = explode(',',$supportedFormats);
        if (!in_array($format,$supportedFormats)) {
             $contentType = $this->getOption('defaultResponseFormat','json');
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
	protected function getController() {
		$expectedFile = trim($this->request->action,'/');
		$basePath = $this->getOption('basePath');
		$controllerClassPrefix = $this->getOption('controllerClassPrefix','modController');
		$controllerClassSeparator = $this->getOption('controllerClassSeparator','_');
		$controllerClassFilePostfix = $this->getOption('controllerClassFilePostfix','.php');

        /* handle [object]/[id] pathing */
        $expectedArray = explode('/',$expectedFile);
        if (empty($expectedArray)) $expectedArray = array(rtrim($expectedFile,'/').'/');
        $id = array_pop($expectedArray);
        if (!file_exists($basePath.$expectedFile.$controllerClassFilePostfix) && !empty($id)) {
            $expectedFile = implode('/',$expectedArray);
            if (empty($expectedFile)) {
                $expectedFile = $id;
                $id = null;
            }
            $this->requestPrimaryKey = $id;
        }

		foreach ($this->iterateDirectories($basePath.'/*'.$controllerClassFilePostfix, GLOB_NOSORT) as $controller) {
            $controller = $basePath != '/' ? str_replace($basePath,'',$controller) : $controller;
            $controller = trim($controller,'/');
            $controllerFile = str_replace(array($controllerClassFilePostfix),array(''),$controller);
            $controllerClass = str_replace(array('/',$controllerClassFilePostfix),array($controllerClassSeparator,''),$controller);
			if (strnatcasecmp($expectedFile, $controllerFile) == 0) {
			    require_once $basePath.$controller;
				return $controllerClassPrefix . $controllerClassSeparator . $controllerClass;
			}
		}
		$this->modx->log(modX::LOG_LEVEL_INFO,'Could not find expected controller: '.$expectedFile);
		return null;
	}

    /**
     * Iterate across directories looking for files based on a pattern
     *
     * @param string $pattern
     * @param int $flags
     * @return array
     */
    public function iterateDirectories($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        $dirs = glob(dirname($pattern) . '/*', GLOB_ONLYDIR|GLOB_NOSORT);

        if ($dirs) {
            foreach ($dirs as $dir) {
                $files = array_merge($files, $this->iterateDirectories($dir . '/' . basename($pattern), $flags));
            }
        }

        return $files;
    }

    /**
     * Send either to the unauthorized page or exit out with a 401
     * @param bool $exit
     */
    public function sendUnauthorized($exit = true) {
        if (!$exit) {
            $this->modx->sendUnauthorizedPage();
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
            @session_write_close();
            exit(0);
        }
    }
}

/**
 * Request class for REST Service, which abstracts the incoming request
 *
 * @package modx
 * @subpackage rest
 */
class modRestServiceRequest {
    /** @var \modRestService $service */
    public $service;
    /** @var string $action The action for the request */
    public $action = 'index';
    /** @var string $format The format the request is asking for */
    public $format = 'json';
    /** @var string $method The HTTP method the  */
    public $method = 'GET';
    /** @var array $headers The HTTP headers on the request */
    public $headers = array();
    /** @var array $parameters The request parameters on the request */
    public $parameters = array();

    /**
     * @param modRestService $service A reference to the modRestService instance
     */
    function __construct(modRestService &$service) {
        $this->service = &$service;
    }

    /**
     * Set or determine the target action (controller) for this request
     *
     * @param string $action
     */
    public function setAction($action = '') {
        if (empty($action)) {
            $requestParameter = $this->service->getOption('requestParameter','_rest');
            $defaultAction = $this->service->getOption('defaultAction','index');
            $action = !empty($_GET[$requestParameter]) ? $_GET[$requestParameter] : $defaultAction;
        }
		$this->_trimString($action);
        $this->action = $action;
    }

    /**
     * Set the response format for this request
     *
     * @param string $format
     */
    public function setFormat($format = 'json') {
		$this->_trimString($format);
		$this->format = $format;
    }

    /**
     * Check for a format suffix (.json, .xml, etc) on the request, properly setting the format if found
     */
    public function checkForSuffix() {
        $checkForSuffix = $this->service->getOption('checkForSuffix', true);
        $formatPos = strpos($this->action,'.');
		if ($checkForSuffix && $formatPos !== false) {
		    $this->format = substr($this->action,$formatPos+1);
		    $this->action = substr($this->action,0,$formatPos);
		}
    }

    /**
     * Set or determine the HTTP request method for this request
     *
     * @param string $method
     */
    public function setMethod($method = '') {
        if (empty($method)) {
		    $method = strtolower($_SERVER['REQUEST_METHOD']);
        }
		$this->_trimString($method);
		$this->method = $method;
    }

    /**
     * Set or collect the headers for this request
     *
     * @param array $headers
     */
    public function setHeaders(array $headers = array()) {
        if (empty($headers)) {
            if (function_exists('apache_request_headers')) {
                $this->headers = apache_request_headers();
            }
            $headers = array();
            $keys = preg_grep('{^HTTP_}i', array_keys($_SERVER));
            foreach ($keys as $val) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($val, 5)))));
                $headers[$key] = $_SERVER[$val];
            }
        }
        array_walk_recursive($headers,array('modRestServiceRequest','_trimString'));
        $this->headers = $headers;
    }

    /**
     * Set the REQUEST parameters for this request
     */
    public function setRequestParameters() {
		switch ($this->method) {
			case 'get':
				$this->parameters = $_GET;
				break;
			case 'post':
			    $this->parameters = array_merge($_POST,$_GET,$this->_collectRequestParameters());
			    $_REQUEST = $this->parameters;
				break;
			case 'put':
			    $this->parameters = array_merge($_POST,$this->_collectRequestParameters());
			    $_REQUEST = $this->parameters;
            	break;
			case 'delete':
			    $this->parameters = array_merge($_GET,$this->_collectRequestParameters());
			    $_REQUEST = $this->parameters;
				break;
			default:
				break;
		}
    }

    /**
     * Properly get request parameters for various HTTP methods and content types
     * @return array
     */
	protected function _collectRequestParameters() {
        $filehandle = fopen('php://input', "r");
        $params = array();
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        $spPos = strpos($contentType, ';');
        if ($spPos !== false) {
            $contentType = substr($contentType, 0, $spPos);
        }
        switch ($contentType) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                $params['filehandle'] = $filehandle;
                break;
            case 'application/xml':
            case 'text/xml':
                $data = stream_get_contents($filehandle);
                fclose($filehandle);
                if (LIBXML_VERSION < 20900 && $this->service->getOption('xmlDisableEntityLoader')) {
                    $disableEntities = libxml_disable_entity_loader(true);
                    $xml = simplexml_load_string($data);
                    libxml_disable_entity_loader($disableEntities);
                }
                else {
                    $xml = simplexml_load_string($data);
                }
                $params = $this->_xml2array($xml);
                break;
            case 'application/json':
            case 'text/json':
                $data = stream_get_contents($filehandle);
                fclose($filehandle);
                $params = $this->service->modx->fromJSON($data);
                $params = (!is_array($params)) ? array() : $params;
                break;
            case 'application/x-www-form-urlencoded':
            default:
                $data = stream_get_contents($filehandle);
                fclose($filehandle);
                parse_str($data, $params);
                break;
        }
        if ($this->service->getOption('trimParameters', false)) {
            array_walk_recursive($this->parameters, array('modRestServiceRequest', '_trimString'));
        }
        return $params;
	}

	/**
     * Trim a value, assuming it is a string
     *
     * @static
     * @param mixed $value
     */
	public static function _trimString(&$value) {
        if (is_string($value)) {
            $value = trim($value);
        }
	}

    /**
     * Convert a SimpleXMLElement object to a multi-dimensional array
     *
     * @param SimpleXMLElement $xml
     * @param mixed $attributesKey
     * @param mixed $childrenKey
     * @param mixed $valueKey
     * @return array|null|string
     */
    protected function _xml2array(SimpleXMLElement $xml,$attributesKey=null,$childrenKey=null,$valueKey=null) {
        if ($childrenKey && !is_string($childrenKey)) $childrenKey = '@children';
        if ($attributesKey && !is_string($attributesKey)) $attributesKey = '@attributes';
        if ($valueKey && !is_string($valueKey)) $valueKey = '@values';

        $return = array();
        $name = $xml->getName();
        $_value = trim((string)$xml);
        if (!strlen($_value)) $_value = null;

        if ($_value !== null){
            if ($valueKey) {
                $return[$valueKey] = $_value;
            } else{
                $return = $_value;
            }
        }

        $children = array();
        $first = true;
        foreach ($xml->children() as $elementName => $child){
            $value = $this->_xml2array($child,$attributesKey, $childrenKey,$valueKey);
            if (isset($children[$elementName])) {
                if (is_array($children[$elementName])) {
                    if ($first) {
                        $temp = $children[$elementName];
                        unset($children[$elementName]);
                        $children[$elementName][] = $temp;
                        $first = false;
                    }
                    $children[$elementName][] = $value;
                } else {
                    $children[$elementName] = array($children[$elementName],$value);
                }
            } else {
                $children[$elementName] = $value;
            }
        }
        if ($children) {
            if ($childrenKey) {
                $return[$childrenKey] = $children;
            } else {
                $return = array_merge($return,$children);
            }
        }
        $attributes = array();
        foreach ($xml->attributes() as $name => $value) {
            $attributes[$name] = trim($value);
        }
        if ($attributes) {
            if ($attributesKey) {
                $return[$attributesKey] = $attributes;
            }
            else if (is_array($attributes) && is_array($return)) {
                $return = array_merge($return, $attributes);
            }
        }
        return $return;
    }
}

/**
 * The REST response class for the service
 *
 * @package modx
 * @subpackage rest
 */
class modRestServiceResponse {
    /** @var string $body The data body of the response */
    public $body;
    /** @var int $status The status code of the response */
    public $status;
    /** @var string $contentType The string content type of the response */
    public $contentType = 'json';
    /** @var array $payload The data payload being sent as the response */
    protected $payload = array();

    /**
     * Map of formats to their parallel content types
     * @var array
     */
	protected static $contentTypes = array(
        'xml'   => 'application/xml',
        'json'  => 'application/json',
        'qs'    => 'text/plain'
    );
    /**
     * Dictionary of response codes and their text descriptions
     * @var array
     */
	protected static $responseCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );

    /**
     * @param modRestService $service A reference to the modRestService instance
     * @param string $body The actual body of the response
     * @param string|int $status The status code for the response
     */
    function __construct(modRestService &$service,$body,$status) {
        $this->service = &$service;
        $this->body = $body;
        $this->status = $status;
    }

    /**
     * Set the content type for this response
     *
     * @param string $contentType
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
    }

    /**
     * Prepare the response, properly formatting the body and generating the payload
     */
    public function prepare() {
		if (!empty($this->body)) {
			$this->payload = array('status' => $this->status, 'body' => $this->getFormattedBody());
		} else {
			$this->contentType = 'qs';
			$this->payload = array('status' => $this->status, 'body' => $this->body);
		}
    }

    /**
     * Format the body based on the content type of the response
     *
     * @access protected
     * @return string
     */
	protected function getFormattedBody() {
	    switch ($this->contentType) {
            case 'xml':
                $data = $this->toXml($this->body);
                break;
            case 'qs':
                $data = http_build_query($this->body);
                break;
            case 'json':
            case 'js':
            default:
                $data = $this->service->modx->toJSON($this->body);
                break;
        }
        return $data;
	}

    /**
     * Send the response back to the client.
     */
	public function send() {
        $contentType = $this->getResponseContentType($this->contentType);
        $status = !empty($this->payload['status']) ? $this->payload['status'] : 200;
        $body = empty($this->payload['body']) ? '' : $this->payload['body'];

		$headers = $_SERVER['SERVER_PROTOCOL'] . ' ' . $status . ' ' . $this->getResponseCodeMessage($status);
		header($headers);
		header('Content-Type: ' . $contentType);
		echo $body;
		if ($this->service->getOption('exitOnResponse',true)) {
            @session_write_close();
            exit(0);
        }
	}

    /**
     * Get the proper response code message for the passed status code
     *
     * @param int $status
     * @return string
     */
	protected function getResponseCodeMessage($status) {
        return (isset(self::$responseCodes[$status])) ? self::$responseCodes[$status] : self::$responseCodes[500];
    }

    /**
     * Get the proper HTTP content type for the passed format
     *
     * @param string $format
     * @return string
     */
	protected function getResponseContentType($format = 'json') {
		return self::$contentTypes[$format];
	}

    /**
     * Convert an array to XML output
     *
     * @param array $data
     * @param string $version
     * @param string $encoding
     * @return string
     */
	protected function toXml($data, $version = '1.0', $encoding = 'UTF-8') {
		$xml = new XMLWriter;
		$xml->openMemory();
		$xml->startDocument($version, $encoding);
		$xml->startElement($this->service->getOption('xmlRootNode','response'));
		$this->_xml($xml, $data);
		$xml->endElement();
		return $xml->outputMemory(true);
	}

    /**
     * Helper method for converting an array to XML output
     *
     * @param XMLWriter $xml
     * @param mixed $data
     * @param string $old_key
     */
	protected function _xml(XMLWriter $xml, $data, $old_key = null) {
        foreach ($data as $key => $value){
            if (is_array($value)){
                if (!is_int($key)) {
                    $xml->startElement($key);
                } else {
                    $singleKey = trim($old_key,'s');
                    $xml->startElement($singleKey);
                }
                $this->_xml($xml, $value, $key);
                $xml->endElement();
                continue;
            }
            $key = (is_int($key)) ? $old_key.$key : $key;
            if (!is_object($value)) {
                $xml->writeElement($key, $value);
            }
        }
    }
}
