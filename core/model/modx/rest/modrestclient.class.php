<?php
/**
 * Handles all REST requests and responses
 *
 * @package modx
 * @subpackage rest
 */
/**
 * The basic REST client for handling REST requests
 *
 * @package modx
 * @subpackage rest
 */
class modRestClient {
    const OPT_PATH = 'path';
    const OPT_PORT = 'port';
    const OPT_RESPONSE_CLASS = 'restResponse.class';
    const OPT_TIMEOUT = 'timeout';
    const OPT_USERAGENT = 'userAgent';
    const OPT_USERPWD = 'userpwd';
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
    }

    /**
     * Get the connection class for the client. Defaults to cURL, then
     * fsockopen. If neither exists, returns false.
     *
     * @access public
     * @return boolean True if a connection can be made.
     */
    public function getConnection() {
        if (function_exists('curl_init')) {
            $className = $this->modx->loadClass('rest.modRestCurlClient','',false,true);
        } else if (function_exists('fsockopen')) {
            $className = $this->modx->loadClass('rest.modRestSockClient','',false,true);
        }

        if ($className) {
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
     * @return modRestResponse The response object.
     */
    public function request($host,$path,$method = 'GET',array $params = array()) {
        if (!is_object($this->conn)) {
            $loaded = $this->getConnection();
            if (!$loaded) return false;
        }
        $this->host = $host;
        $response = $this->conn->request($this->host,$path,$method,$params);

        $responseClass = $this->modx->getOption(modRestClient::OPT_RESPONSE_CLASS,$this->config,'modRestResponse');
        $this->response = new $responseClass($this,$response);

        return $this->response;
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

/**
 * A class for handling REST responses
 *
 * @package modx
 * @subpackage rest
 */
class modRestResponse {
    /**
     * The constructor for the modRestResponse class.
     *
     * @param modRestClient &$client A reference to the modRestClient instance.
     * @param string $response The response from the REST server.
     * @return modRestResponse
     */
    function __construct(modRestClient &$client, $response) {
        $this->client =& $client;
        $this->response = $response;
        $this->toXml();
    }

    /**
     * Translates the current response object to a SimpleXMLElement instance
     *
     * @access public
     * @return SimpleXMLElement
     */
    public function toXml() {
        if ($this->xml instanceof SimpleXMLElement) return $this->xml;

        $this->xml = simplexml_load_string($this->response);
        if (!$this->xml) {
            $this->client->modx->log(xPDO::LOG_LEVEL_ERROR,'Could not connect to provider at: '.$this->client->host);
            $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><error><message>'.$this->client->modx->lexicon('provider_err_blank_response').'</message></error>');
            return $this->xml;
        }
        return $this->xml;
    }

    /**
     * Checks to see whether or not the response is an error response
     *
     * @access public
     * @return boolean True if the response is an error
     */
    public function isError() {
        $this->toXml();
        return $this->xml->getName() == 'error';
    }

    /**
     * Returns an error message, if any.
     *
     * @access public
     * @return string The error message
     */
    public function getError() {
        if (empty($this->xml) || !($this->xml instanceof SimpleXMLElement)) return '';

        return (string)$this->xml->message;
    }
}