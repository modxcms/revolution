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


use DOMDocument;
use DOMNode;
use MODX\Revolution\modX;

/**
 * Request class for handling REST requests
 *
 * @package MODX\Revolution\Rest
 */
class RestClientRequest
{
    /** @var modX $modx */
    public $modx;
    /** @var array $config */
    public $config = [];
    /** @var string $url */
    public $url;
    /** @var string $method */
    public $method = 'GET';
    /** @var mixed $handle */
    public $handle;
    /** @var array $requestParameters */
    public $requestParameters = [];
    /** @var array $requestOptions */
    public $requestOptions = [];
    /** @var array $headers */
    public $headers = [];
    /** @var array $defaultRequestParameters */
    public $defaultRequestParameters = [];
    /** @var string $rootNode */
    public $rootNode = 'request';

    /**
     * The RestClientRequest constructor
     *
     * @param modX  $modx   A reference to the modX instance
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $this->config = array_merge($this->config, $config);
        if (!empty($this->config['headers'])) {
            $this->setHeaders($this->config['headers']);
        }
        if (!empty($this->config['defaultParameters'])) {
            $this->defaultRequestParameters = $this->config['defaultParameters'];
        }
        $this->_setDefaultRequestOptions();
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setOption($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
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
     * Set the root node of the request. Only used for XML requests.
     *
     * @param string $node
     */
    public function setRootNode($node)
    {
        $this->rootNode = $node;
    }

    /**
     * Set the request parameters for the request.
     *
     * @param array $parameters
     */
    public function setRequestParameters(array $parameters)
    {
        $this->requestParameters = array_merge($this->defaultRequestParameters, $parameters);
    }

    /**
     * Set the HTTP headers on the request
     *
     * @param array $headers
     * @param bool  $merge
     */
    public function setHeaders($headers = [], $merge = false)
    {
        $this->headers = $merge ? array_merge($this->headers, $headers) : $headers;
    }

    /**
     * Execute the request, properly preparing it, setting the URL and sending the request via cURL
     *
     * @param string $path
     * @param string $method
     * @param array  $parameters
     * @param array  $headers
     *
     * @return RestClientResponse
     */
    public function execute($path, $method = 'GET', $parameters = [], $headers = [])
    {
        $this->url = $path;
        $this->method = strtoupper($method);
        $this->setRequestParameters($parameters);
        if (!empty($headers)) {
            $this->setHeaders($headers, true);
        }

        $this->prepare();

        return $this->send();
    }

    /**
     * Prepare the request for sending
     */
    protected function prepare()
    {
        $this->prepareHandle();
        $this->prepareAuthentication();
        $this->prepareUrl();
        $this->preparePayload();
        $this->prepareHeaders();
        $this->prepareRequestOptions();
    }

    /**
     * Send the request over the wire
     *
     * @return RestClientResponse
     */
    protected function send()
    {
        $this->modx->log(modX::LOG_LEVEL_INFO,
            '[Rest] Sending request to ' . $this->url . ' with parameters: ' . print_r($this->requestParameters, true));
        curl_setopt_array($this->handle, $this->requestOptions);
        $result = curl_exec($this->handle);
        $headerSize = curl_getinfo($this->handle, CURLINFO_HEADER_SIZE);
        $response = new RestClientResponse($this->modx, $result, $headerSize, $this->config);
        $info = (object)curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
        $response->setResponseInfo($info);
        $error = curl_error($this->handle);
        $response->setResponseError($error);
        curl_close($this->handle);

        return $response;
    }

    /**
     * Load the request handle
     *
     * @return mixed
     */
    protected function prepareHandle()
    {
        $this->handle = curl_init();

        return $this->handle;
    }

    /**
     * Set any authentication options for this request
     */
    protected function prepareAuthentication()
    {
        $username = $this->getOption('username', '');
        $password = $this->getOption('password', '');
        if (!empty($username)) {
            $this->requestOptions[CURLOPT_USERPWD] = $username . (!empty($password) ? ':' . $password : '');
        }
    }

    /**
     * Set any HTTP headers and load them into the request options
     */
    protected function prepareHeaders()
    {
        if (!empty($this->headers)) {
            if (empty($this->requestOptions[CURLOPT_HTTPHEADER])) {
                $this->requestOptions[CURLOPT_HTTPHEADER] = [];
            }
            foreach ($this->headers as $key => $value) {
                $this->requestOptions[CURLOPT_HTTPHEADER][] = sprintf("%s: %s", $key, $value);
            }
        }
    }

    /**
     * Prepare the URL, prefixing the baseUrl if set, and setting the format suffix, if wanted
     *
     * @return mixed
     */
    protected function prepareUrl()
    {
        $format = $this->getOption('format', 'json');
        $suppressSuffix = $this->getOption('suppressSuffix', false);
        if (!empty($format) && !$suppressSuffix) {
            $this->url .= '.' . $format;
        }

        if ($this->method != 'POST' && count($this->requestParameters)) {
            $this->url .= strpos($this->url, '?') ? '&' : '?';
            $this->url .= $this->_formatQuery($this->requestParameters);
        }

        $baseUrl = $this->getOption('baseUrl', false);
        if (!empty($baseUrl)) {
            if ((!empty($this->url) && $this->url[0] != '/') && substr($baseUrl, -1) != '/') {
                $this->url = '/' . $this->url;
            }
            $this->url = $baseUrl . $this->url;
        }
        $this->requestOptions[CURLOPT_URL] = $this->url;
        return $this->url;
    }

    /**
     * Prepare the payload of parameters to be sent with the request
     */
    protected function preparePayload()
    {
        if ($this->method != 'GET') {
            $format = $this->getOption('format', 'json');
            switch ($format) {
                case 'json':
                    if (empty($this->requestOptions[CURLOPT_HTTPHEADER])) {
                        $this->requestOptions[CURLOPT_HTTPHEADER] = [];
                    }
                    $this->requestOptions[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json; charset=utf-8';
                    if (!empty($this->requestParameters)) {
                        $params = $this->requestParameters;
                        if (!empty($this->config['useRootNodeInJSON'])) {
                            $params = [$this->rootNode => $params];
                        }
                        $json = json_encode($params);
                        $this->requestOptions[CURLOPT_POSTFIELDS] = $json;
                    }
                    break;
                case 'xml':
                    if (empty($this->requestOptions[CURLOPT_HTTPHEADER])) {
                        $this->requestOptions[CURLOPT_HTTPHEADER] = [];
                    }
                    $this->requestOptions[CURLOPT_HTTPHEADER][] = 'Content-Type: application/xml; charset=utf-8';
                    if (!empty($this->requestParameters)) {
                        $xml = $this->toXml($this->requestParameters, $this->rootNode);
                        $this->requestOptions[CURLOPT_POSTFIELDS] = $xml;
                    }
                    break;
                default:
                    $this->requestOptions[CURLOPT_POSTFIELDS] = $this->_formatQuery($this->requestParameters);
                    break;
            }
        }

        if ($this->method == 'POST') {
            $this->requestOptions[CURLOPT_POST] = true;
        } elseif ($this->method != 'GET') {
            $this->requestOptions[CURLOPT_CUSTOMREQUEST] = $this->method;
        }
    }

    /**
     * Prepare the request options to be sent, setting them on the cURL handle
     */
    protected function prepareRequestOptions()
    {
        $curlOptions = $this->getOption('curlOptions');
        if (!empty($curlOptions) && is_array($curlOptions)) {
            foreach ($curlOptions as $key => $value) {
                $this->requestOptions[$key] = $value;
            }
        }
    }

    /**
     * Setup the default request options
     */
    private function _setDefaultRequestOptions()
    {
        $this->requestOptions = [
            CURLOPT_HEADER => $this->getOption('header', true),
            CURLOPT_RETURNTRANSFER => $this->getOption('returnTransfer', true),
            CURLOPT_FOLLOWLOCATION => $this->getOption('followLocation', true),
            CURLOPT_TIMEOUT => $this->getOption('timeout', 240),
            CURLOPT_CONNECTTIMEOUT => $this->getOption('connectTimeout', 0),
            CURLOPT_DNS_CACHE_TIMEOUT => $this->getOption('dnsCacheTimeout', 120),
            CURLOPT_VERBOSE => $this->getOption('verbose', false),
            CURLOPT_SSL_VERIFYHOST => $this->getOption('sslVerifyhost', 2),
            CURLOPT_SSL_VERIFYPEER => $this->getOption('sslVerifypeer', false),
            CURLOPT_COOKIE => $this->getOption('cookie', ''),
            CURLOPT_COOKIEFILE => $this->getOption('cookieFile', ''),
            CURLOPT_ENCODING => $this->getOption('encoding', ''),
            CURLOPT_REFERER => $this->getOption('referer', ''),
            CURLOPT_USERAGENT => $this->getOption('userAgent', ''),
            CURLOPT_NETRC => $this->getOption('netrc', false),
            CURLOPT_HTTPPROXYTUNNEL => $this->getOption('httpProxyTunnel', false),
            CURLOPT_FRESH_CONNECT => $this->getOption('freshConnect', false),
            CURLOPT_FORBID_REUSE => $this->getOption('forbidReuse', false),
            CURLOPT_CRLF => $this->getOption('crlf', false),
            CURLOPT_AUTOREFERER => $this->getOption('autoreferer', false),
            CURLOPT_MAXREDIRS => $this->getOption('maxRedirs', 3),
        ];

        $proxy = $this->getOption('proxy', false);
        if (!empty($proxy)) {
            $this->requestOptions = array_merge($this->requestOptions, [
                CURLOPT_PROXY => $proxy,
                CURLOPT_PROXYAUTH => $this->getOption('proxyAuth', CURLAUTH_BASIC),
                CURLOPT_PROXYPORT => $this->getOption('proxyPort', 80),
                CURLOPT_PROXYTYPE => $this->getOption('proxyType', CURLPROXY_HTTP),
            ]);
            $username = $this->getOption('proxyUsername');
            $password = $this->getOption('proxyPassword', '');
            if (!empty($username)) {
                $this->requestOptions[CURLOPT_PROXYUSERPWD] = $username . ':' . $password;
            }
        }
    }

    /**
     * Format an array of parameters into a query string
     *
     * @param array $parameters
     *
     * @return string
     */
    private function _formatQuery(array $parameters)
    {
        $query = http_build_query($parameters);

        return rtrim($query);
    }

    /**
     * @param array  $parameters
     * @param string $rootNode
     *
     * @return string
     */
    public function toXml($parameters, $rootNode)
    {
        $doc = new DOMDocument("1.0", 'UTF-8');
        $root = $doc->appendChild($doc->createElement($rootNode));
        $this->_populateXmlDoc($doc, $root, $parameters);

        return $doc->saveXML();
    }

    /**
     * @param DOMDocument   $doc
     * @param DOMNode       $node
     * @param array|DOMNode $parameters
     */
    protected function _populateXmlDoc(&$doc, &$node, &$parameters)
    {
        foreach ($parameters as $key => $val) {
            if (is_array($val)) {
                if (empty($val)) {
                    continue;
                }
                $attribute_node = $node->appendChild($doc->createElement($key));
                foreach ($val as $child => $childValue) {
                    if (is_null($child) || is_null($childValue)) {
                        continue;
                    } elseif (is_string($child) && !is_null($childValue)) {
                        // e.g. "<items><property>1000</property></items>"
                        $attribute_node->appendChild($doc->createElement($child, $childValue));
                    } elseif (is_int($child) && !is_null($childValue)) {
                        if (is_object($childValue)) {
                            // e.g. "<items><item>...</item></items>"
                            $this->_populateXmlDoc($doc, $attribute_node, $childValue);
                        } elseif (substr($key, -1) == "s") {
                            // e.g. "<items><item>gold</item><item>monthly</item></items>"
                            $attribute_node->appendChild($doc->createElement(substr($key, 0, -1), $childValue));
                        }
                    }
                }
            } elseif (is_object($val)) {
                $this->_populateXmlDoc($doc, $node, $val);
            } else {
                $node->appendChild($doc->createElement($key, $val));
            }
        }
    }
}
