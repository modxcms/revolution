<?php

namespace MODX\Rest;

use MODX\MODX;

/**
 * Response class for REST requests
 *
 * @package modx
 * @subpackage rest
 */
class RestClientResponse
{
    /** @var MODX $modx */
    public $modx;
    /** @var array $config */
    public $config = [];
    /** @var string $response */
    public $response;
    /** @var int $headerSize */
    public $headerSize = 0;
    /** @var string $responseBody */
    public $responseBody;
    /** @var string $responseInfo */
    public $responseInfo;
    /** @var string $responseError */
    public $responseError;
    /** @var mixed $responseHeaders */
    public $responseHeaders;


    /**
     * Constructor for RestClientResponse class.
     *
     * @param MODX $modx A reference to the MODX instance
     * @param string $response The response data
     * @param int $headerSize The size of the response header, in bytes
     * @param array $config An array of configuration options
     */
    function __construct(MODX &$modx, $response = '', $headerSize = 0, array $config = [])
    {
        $this->modx =& $modx;
        $this->config = array_merge($this->config, $config);
        $this->response = $response;
        $this->headerSize = $headerSize;
        $this->setResponseBody($response);
    }


    /**
     * Set and parse the response body
     *
     * @param string $result
     */
    public function setResponseBody($result)
    {
        $this->responseBody = $this->_parse($result);
    }


    /**
     * Set the response info
     *
     * @param string $info
     */
    public function setResponseInfo($info)
    {
        $this->responseInfo = $info;
    }


    /**
     * Set the response error, if any
     *
     * @param string $error
     */
    public function setResponseError($error)
    {
        $this->responseError = $error;
    }


    /**
     * Return the processed result based on the format the response was returned in
     *
     * @return array
     */
    public function process()
    {
        switch ($this->config['format']) {
            case 'xml':
                $result = $this->fromXML($this->responseBody);
                break;
            case 'json':
            default:
                $result = json_decode($this->responseBody, true);
                break;
        }

        return !empty($result) ? $result : [];
    }


    /**
     * Parse the result
     *
     * @param string $result
     *
     * @return string
     */
    public function _parse($result)
    {
        $headers = [];
        while ($line = strtok("\n")) {
            if (strlen(trim($line)) == 0) break;

            list($key, $value) = explode(':', $line, 2);
            $key = trim(strtolower(str_replace('-', '_', $key)));
            $value = trim($value);
            if (empty($headers[$key])) {
                $headers[$key] = $value;
            } elseif (is_array($headers[$key])) {
                $headers[$key][] = $value;
            } else {
                $headers[$key] = [$headers[$key], $value];
            }
        }

        $this->responseHeaders = (object)$headers;

        return substr($result, $this->headerSize);
    }


    /**
     * Convert JSON into an array
     *
     * @param string $data
     *
     * @return array
     */
    protected function fromJSON($data)
    {
        return json_decode($data, true);
    }


    /**
     * Convert XML into an array
     *
     * @param string|\SimpleXMLElement $xml
     * @param mixed $attributesKey
     * @param mixed $childrenKey
     * @param mixed $valueKey
     *
     * @return array
     */
    protected function fromXML($xml, $attributesKey = null, $childrenKey = null, $valueKey = null)
    {
        if (is_string($xml)) {
            $xml = simplexml_load_string($xml);
        }
        if (empty($xml)) {
            return [];
        }
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
        $_value = trim((string)$xml);
        if (!strlen($_value)) {
            $_value = null;
        };

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
            $value = $this->fromXML($child, $attributesKey, $childrenKey, $valueKey);
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
            } elseif (is_array($attributes) && is_array($return)) {
                $return = array_merge($return, $attributes);
            }
        }

        return $return;
    }
}