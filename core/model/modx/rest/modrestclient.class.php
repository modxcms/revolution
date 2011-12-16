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

/**
 * A class for handling REST responses
 *
 * @package modx
 * @subpackage rest
 */
class modRestResponse {
    /**
     * @var string The type of response format
     */
    public $responseType = 'xml';
    /** @var SimpleXMLElement $xml */
    public $xml = null;
    /** @var string $json */
    public $json = null;
    
    /**
     * The constructor for the modRestResponse class.
     *
     * @param modRestClient &$client A reference to the modRestClient instance.
     * @param string $response The response from the REST server.
     * @param string $responseType The type of response, either xml or json
     * @return modRestResponse
     */
    function __construct(modRestClient &$client, $response, $responseType = 'xml') {
        $this->client =& $client;
        $this->response = $response;
        if ($responseType == 'xml') {
            $this->toXml();
        } else if ($responseType == 'json') {
            $this->fromJSON();
        }
    }

    /**
     * Translates the current response object to a SimpleXMLElement instance
     *
     * @access public
     * @return SimpleXMLElement
     */
    public function toXml() {
        if (!empty($this->xml) && $this->xml instanceof SimpleXMLElement) return $this->xml;

        try {
            $this->xml = simplexml_load_string($this->response);
        } catch (Exception $e) {
            $this->client->modx->log(xPDO::LOG_LEVEL_ERROR,'Could not parse XML response from provider: '.$this->response);
        }
        if (!$this->xml) {
            $this->client->modx->log(xPDO::LOG_LEVEL_ERROR,'Could not connect to provider at: '.$this->client->host);
            $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><error><message>'.$this->client->modx->lexicon('provider_err_blank_response').'</message></error>');
            return $this->xml;
        }
        return $this->xml;
    }

    /**
     * Translates current response from JSON to an array
     *
     * @access public
     * @return array
     */
    public function fromJSON() {
        if (!empty($this->json)) return $this->json;

        $this->json = $this->client->modx->fromJSON($this->response);
        return $this->json;
    }

    /**
     * Checks to see whether or not the response is an error response
     *
     * @access public
     * @return boolean True if the response is an error
     */
    public function isError() {
        if ($this->responseType == 'xml') {
            $this->toXml();
            $isError = $this->xml->getName() == 'error';
        } else {
            $this->fromJSON();
            $isError = !empty($this->json['error']) ? true : false;
        }
        return $isError;
    }

    /**
     * Returns an error message, if any.
     *
     * @access public
     * @return string The error message
     */
    public function getError() {
        $message = '';
        if ($this->responseType == 'xml') {
            if (empty($this->xml) || !($this->xml instanceof SimpleXMLElement)) {
                $message = '';
            } else {
                $message = (string)$this->xml->message;
            }
        } else {
            $this->fromJSON();
            $message = !empty($this->json['error']) && !empty($this->json['message']) ? $this->json['message'] : '';
        }
        return $message;
    }
}