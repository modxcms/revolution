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

/**
 * The basic REST client for handling REST requests
 *
 * @deprecated To be removed in 2.3. See modRest instead.
 *
 * @package MODX\Revolution\Rest
 */
class modRestClient {
    /**
     * @const The path of the request
     */
    const OPT_PATH = 'path';
    /**
     * @const The port of the request
     */
    const OPT_PORT = 'port';
    /**
     * @const The response class to use when generating the response object
     */
    const OPT_RESPONSE_CLASS = 'restResponse.class';
    /**
     * @const The number of seconds before the request times out
     */
    const OPT_TIMEOUT = 'timeout';
    /**
     * @const The user-agent sent in the request
     */
    const OPT_USERAGENT = 'userAgent';
    /**
     * @const The user password to send with the request
     */
    const OPT_USERPWD = 'userpwd';
    /**
     * @const The authentication type for the request
     */
    const OPT_AUTHTYPE = 'authtype';

    /**
     * @var modX $modx A reference to the modX instance.
     * @access public
     */
    public $modx = null;
    /**
     * @var array $config The configuration array.
     * @access public
     */
    public $config = array();
    /**
     * @var modRestClient $conn The client connection instance to use.
     * @access public
     */
    public $conn = null;
    /**
     * @var modRestResponse $response The response object after a request is
     * made.
     * @access public
     */
    public $response = null;
    /**
     * @access public
     * @var string The expected response type
     */
    public $responseType = 'xml';
    /**
     * The current host to connect to
     * @var string $host
     */
    public $host;

    /**
     * The constructor for the modRestClient class. Assigns a modX instance
     * reference and sets up the basic config array.
     *
     * @param modX &$modx A reference to the modX instance.
     * @param array $config An array of configuration options.
     * @return modRestClient
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            modRestClient::OPT_PORT => 80,
            modRestClient::OPT_TIMEOUT => 30,
            modRestClient::OPT_PATH => '/',
            modRestClient::OPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)"
        ),$config);
        $this->modx->deprecated('2.3.0', 'Use the modRest classes instead.');
    }

    /**
     * Get the connection class for the client. Defaults to cURL, then
     * fsockopen. If neither exists, returns false.
     *
     * @access public
     * @return boolean True if a connection can be made.
     */
    public function getConnection() {
        $className = false;
        if (function_exists('curl_init')) {
            $className = $this->modx->loadClass('rest.modRestCurlClient','',false,true);
        } else if (function_exists('fsockopen')) {
            $className = $this->modx->loadClass('rest.modRestSockClient','',false,true);
        }

        if (!empty($className)) {
            $this->conn = new $className($this->modx,$this->config);
        }
        return is_object($this->conn);
    }

    /**
     * Send a REST request
     *
     * @access public
     * @param string $host The host of the REST server.
     * @param string $path The path to request to on the REST server.
     * @param string $method The HTTP method to use for the request. May be GET,
     * PUT or POST.
     * @param array $params An array of parameters to send with the request.
     * @param array $options An array of options to pass to the request.
     * @return modRestResponse The response object.
     */
    public function request($host,$path,$method = 'GET',array $params = array(),array $options = array()) {
        if (!is_object($this->conn)) {
            $loaded = $this->getConnection();
            if (!$loaded) return false;
        }
        $this->host = $host;
        $response = $this->conn->request($this->host,$path,$method,$params,$options);

        $responseClass = $this->modx->getOption(modRestClient::OPT_RESPONSE_CLASS,$this->config,'modRestResponse');
        $this->response = new $responseClass($this,$response,$this->responseType);

        return $this->response;
    }

    /**
     * Sets the response type
     *
     * @param string $type The type to set, either json or xml
     */
    public function setResponseType($type) {
        $this->responseType = $type;
    }

    /**
     * Translates a SimpleXMLElement object into an array.
     *
     * @access public
     * @param SimpleXMLElement $obj
     * @param array &$arr The reference array to store the results in.
     * @return boolean True if successful.
     */
    public function xml2array($obj, &$arr) {
        if (!($obj instanceof SimpleXMLElement)) return false;
        $children = $obj->children();
        foreach ($children as $elementName => $node)
        {
            $nextIdx = count($arr);
            $arr[$nextIdx] = array();
            $arr[$nextIdx]['name'] = strtolower((string)$elementName);
            $arr[$nextIdx]['attributes'] = array();
            $attributes = $node->attributes();
            foreach ($attributes as $attributeName => $attributeValue) {
                $attribName = strtolower(trim((string)$attributeName));
                $attribVal = trim((string)$attributeValue);
                $arr[$nextIdx]['attributes'][$attribName] = $attribVal;
            }
            $text = (string)$node;
            $text = trim($text);
            if (strlen($text) > 0) {
                $arr[$nextIdx]['text'] = $text;
            }
            $arr[$nextIdx]['children'] = array();
            $this->xml2array($node, $arr[$nextIdx]['children']);
        }
        return true;
    }
}
