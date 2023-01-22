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


use MODX\Revolution\modX;
use SimpleXMLElement;

/**
 * Request class for REST Service, which abstracts the incoming request
 *
 * @package MODX\Revolution\Rest
 */
class modRestServiceRequest
{
    /** @var modRestService $service */
    public $service;
    /** @var string $action The action for the request */
    public $action = 'index';
    /** @var string $format The format the request is asking for */
    public $format = 'json';
    /** @var string $method The HTTP method the */
    public $method = 'GET';
    /** @var array $headers The HTTP headers on the request */
    public $headers = [];
    /** @var array $parameters The request parameters on the request */
    public $parameters = [];

    /**
     * @param modRestService $service A reference to the modRestService instance
     */
    function __construct(modRestService &$service)
    {
        $this->service = &$service;
    }

    /**
     * Set or determine the target action (controller) for this request
     *
     * @param string $action
     */
    public function setAction($action = '')
    {
        if (empty($action)) {
            $requestParameter = $this->service->getOption('requestParameter', '_rest');
            $defaultAction = $this->service->getOption('defaultAction', 'index');
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
    public function setFormat($format = 'json')
    {
        $this->_trimString($format);
        $this->format = $format;
    }

    /**
     * Check for a format suffix (.json, .xml, etc) on the request, properly setting the format if found
     */
    public function checkForSuffix()
    {
        $checkForSuffix = $this->service->getOption('checkForSuffix', true);
        $formatPos = strpos($this->action, '.');
        if ($checkForSuffix && $formatPos !== false) {
            $this->format = substr($this->action, $formatPos + 1);
            $this->action = substr($this->action, 0, $formatPos);
        }
    }

    /**
     * Set or determine the HTTP request method for this request
     *
     * @param string $method
     */
    public function setMethod($method = '')
    {
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
    public function setHeaders(array $headers = [])
    {
        if (empty($headers)) {
            if (function_exists('apache_request_headers')) {
                $headers = apache_request_headers();
            } else {
                $headers = [];
                $keys = preg_grep('{^HTTP_}i', array_keys($_SERVER));
                foreach ($keys as $val) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($val, 5)))));
                    $headers[$key] = $_SERVER[$val];
                }
            }
        }
        array_walk_recursive($headers, [$this, '_trimString']);
        $this->headers = $headers;
    }

    /**
     * Set the REQUEST parameters for this request
     */
    public function setRequestParameters()
    {
        switch ($this->method) {
            case 'get':
                $this->parameters = $_GET;
                break;
            case 'post':
                $this->parameters = array_merge($_POST, $_GET, $this->_collectRequestParameters());
                $_REQUEST = $this->parameters;
                break;
            case 'put':
                $this->parameters = array_merge($_POST, $this->_collectRequestParameters());
                $_REQUEST = $this->parameters;
                break;
            case 'delete':
                $this->parameters = array_merge($_GET, $this->_collectRequestParameters());
                $_REQUEST = $this->parameters;
                break;
            default:
                break;
        }
        if ($this->service->getOption('sanitize', false)) {
            $this->sanitizeRequest();
        }
    }

    /**
     * Sanitize the request parameters
     *
     * @return void
     */
    protected function sanitizeRequest()
    {
        $modxtags = array_values($this->service->modx->sanitizePatterns);
        $this->parameters = modX:: sanitize($this->parameters, $modxtags);

        modX:: sanitize($_COOKIE, $modxtags);
        modX:: sanitize($_REQUEST, $modxtags);
    }

    /**
     * Properly get request parameters for various HTTP methods and content types
     *
     * @return array
     */
    protected function _collectRequestParameters()
    {
        $filehandle = fopen('php://input', "r");
        $params = [];
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
                $xml = simplexml_load_string($data);
                $params = $this->_xml2array($xml);
                break;
            case 'application/json':
            case 'text/json':
                $data = stream_get_contents($filehandle);
                fclose($filehandle);
                $params = $this->service->modx->fromJSON($data);
                $params = (!is_array($params)) ? [] : $params;
                break;
            case 'application/x-www-form-urlencoded':
            default:
                $data = stream_get_contents($filehandle);
                fclose($filehandle);
                parse_str($data, $params);
                break;
        }
        if ($this->service->getOption('trimParameters', false)) {
            array_walk_recursive($this->parameters, [$this, '_trimString']);
        }

        return $params;
    }

    /**
     * Trim a value, assuming it is a string
     *
     * @static
     *
     * @param mixed $value
     */
    public static function _trimString(&$value)
    {
        if (is_string($value)) {
            $value = trim($value);
        }
    }

    /**
     * Convert a SimpleXMLElement object to a multi-dimensional array
     *
     * @param SimpleXMLElement $xml
     * @param mixed            $attributesKey
     * @param mixed            $childrenKey
     * @param mixed            $valueKey
     *
     * @return array|null|string
     */
    protected function _xml2array(SimpleXMLElement $xml, $attributesKey = null, $childrenKey = null, $valueKey = null)
    {
        if ($childrenKey && !is_string($childrenKey)) {
            $childrenKey = '@children';
        }
        if ($attributesKey && !is_string($attributesKey)) {
            $attributesKey = '@attributes';
        }
        if ($valueKey && !is_string($valueKey)) {
            $valueKey = '@values';
        }

        $return = [];
        $name = $xml->getName();
        $_value = trim((string)$xml);
        if (!strlen($_value)) {
            $_value = null;
        }

        if ($_value !== null) {
            if ($valueKey) {
                $return[$valueKey] = $_value;
            } else {
                $return = $_value;
            }
        }

        $children = [];
        $first = true;
        foreach ($xml->children() as $elementName => $child) {
            $value = $this->_xml2array($child, $attributesKey, $childrenKey, $valueKey);
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
                    $children[$elementName] = [$children[$elementName], $value];
                }
            } else {
                $children[$elementName] = $value;
            }
        }
        if ($children) {
            if ($childrenKey) {
                $return[$childrenKey] = $children;
            } else {
                $return = array_merge($return, $children);
            }
        }
        $attributes = [];
        foreach ($xml->attributes() as $name => $value) {
            $attributes[$name] = trim($value);
        }
        if ($attributes) {
            if ($attributesKey) {
                $return[$attributesKey] = $attributes;
            } else {
                if (is_array($attributes) && is_array($return)) {
                    $return = array_merge($return, $attributes);
                }
            }
        }
        if (is_array($return) && empty($return)) {
            $return = '';
        }

        return $return;
    }
}
