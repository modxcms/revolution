<?php
/**
 * @package modx
 * @subpackage rest
 */
require_once dirname(__FILE__) . '/modrestclient.class.php';
/**
 * @package modx
 * @subpackage rest
 */
class modRestCurlClient extends modRestClient {

    function __construct(modX &$modx,array $config = array()) {
        parent::__construct($modx, $config);
        $this->config = array_merge(array(
        ),$this->config);
    }
    /**
     * Extends modRestClient::request to provide cURL specific request handling
     *
     * {@inheritdoc}
     */
    public function request($host,$path,$method = 'GET',array $params = array(),array $options = array()) {
        /* start our cURL connection */
        $ch = curl_init();

        /* setup request */
        $this->setUrl($ch,$host,$path,$method,$params);
        $this->setAuth($ch,$options);
        $this->setProxy($ch,$options);
        $this->setOptions($ch,$options);

        /* execute request */
        $result = trim(curl_exec($ch));
        
        /* make sure to close connection */
        curl_close($ch);

        return $result;
    }

    /**
     * Configure and set the URL to use, along with any request parameters.
     *
     * @param resource $ch The cURL connection resource
     * @see modRestClient::request for parameter documentation.
     */
    public function setUrl($ch,$host,$path,$method = 'GET',array $params = array(),array $options = array()) {
        $q = http_build_query($params);
        switch ($method) {
            case 'GET':
                $path .= '?'.$q;
                break;
            case 'POST':
                curl_setopt($ch,CURLOPT_POST,1);
                if (!empty($options['contentType']) && $options['contentType'] != 'xml') {
                    curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
                } else {
                    curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                    $xml = ArrayToXML::toXML($params,!empty($options['rootNode']) ? $options['rootNode'] : 'request');
                    curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
                }
                break;
        }
        /* prevent invalid xhtml ampersands in request path */
        $url = str_replace('&amp;', '&', $host.$path);
        return curl_setopt($ch, CURLOPT_URL,$url);
    }

    /**
     * Set up cURL-specific options
     * 
     * @param resource $ch The cURL connection resource
     * @param array $options An array of options
     */
    public function setOptions($ch,array $options = array()) {
        /* always return us the result */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        /* we dont want header gruft */
        curl_setopt($ch, CURLOPT_HEADER, 0);
        /* default timeout to 30 seconds */
        curl_setopt($ch, CURLOPT_TIMEOUT,$this->config[modRestClient::OPT_TIMEOUT]);
        /* disable verifypeer since it's not helpful on most environments */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        /* send a useragent to allow proper responses */
        curl_setopt($ch, CURLOPT_USERAGENT,$this->config[modRestCurlClient::OPT_USERAGENT]);

        /* can only use follow location if safe_mode and open_basedir are off */
        $safeMode = ini_get('safe_mode');
        $openBasedir = ini_get('open_basedir');
        if (empty($safeMode) && empty($openBasedir)) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }
    }

    /**
     * Set up authentication configuration , if specified, to be used with REST request.
     *
     * @param resource $ch The cURL connection resource.
     * @param array $options An array of options
     * @return boolean True if authentication was used.
     */
    public function setAuth($ch,array $options = array()) {
        $auth = false;
        if (!empty($options[modRestClient::OPT_USERPWD])) {
            $options[modRestClient::OPT_AUTHTYPE] = $this->modx->getOption(modRestClient::OPT_AUTHTYPE,$options,'BASIC');
            switch ($options[modRestClient::OPT_AUTHTYPE]) {
                case 'ANY': curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); break;
                case 'ANYSAFE': curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANYSAFE); break;
                case 'DIGEST': curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); break;
                case 'GSSNEGOTIATE': curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_GSSNEGOTIATE); break;
                case 'NTLM': curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); break;
                default: case 'BASIC': curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); break;
            }
            $auth = curl_setopt($ch, CURLOPT_USERPWD, !empty($options[modRestClient::OPT_USERPWD]) ? $options[modRestClient::OPT_USERPWD] : 'username:password');
        }
        return $auth;
    }

    /**
     * Set up proxy configuration , if specified, to be used with REST request.
     * 
     * @param resource $ch The cURL connection resource.
     * @param array $options An array of options
     * @return boolean True if the proxy was setup.
     */
    public function setProxy($ch,array $options = array()) {
        $proxyEnabled = false;
        /* if proxy is set, attempt to use it */
        $proxyHost = $this->modx->getOption('proxy_host',null,'');
        if (!empty($proxyHost)) {
            $proxyEnabled = curl_setopt($ch, CURLOPT_PROXY,$proxyHost);
            $proxyPort = $this->modx->getOption('proxy_port',null,'');
            if (!empty($proxyPort)) {
                curl_setopt($ch, CURLOPT_PROXYPORT,$proxyPort);
            }
            $proxyUserpwd = $this->modx->getOption('proxy_username',null,'');
            if (!empty($proxyUserpwd)) {
                $proxyAuthType = $this->modx->getOption('proxy_auth_type',null,'BASIC');
                curl_setopt($ch, CURLOPT_PROXYAUTH,$proxyAuthType);
                $proxyUserpwd .= ':'.$this->modx->getOption('proxy_password',null,'');
                curl_setopt($ch, CURLOPT_PROXYUSERPWD,$proxyUserpwd);
            }
        }
        return $proxyEnabled;
    }
}

if (!class_exists('ArrayToXML')) {
class ArrayToXML {
    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXML( $data, $rootNodeName = 'ResultSet', &$xml=null ) {

        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if ( ini_get('zend.ze1_compatibility_mode') == 1 ) ini_set ( 'zend.ze1_compatibility_mode', 0 );
        if ( is_null( $xml ) ) $xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><'.$rootNodeName.'></'.$rootNodeName.'>');

        // loop through the data passed in.
        foreach( $data as $key => $value ) {

            // no numeric keys in our xml please!
            if ( is_numeric( $key ) ) {
                $numeric = 1;
                $key = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            // if there is another array found recrusively call this function
            if ( is_array( $value ) ) {
                $node = ArrayToXML::is_assoc( $value ) || $numeric ? $xml->addChild( $key ) : $xml;

                // recrusive call.
                if ( $numeric ) $key = 'anon';
                ArrayToXML::toXml( $value, $key, $node );
            } else {

                // add single node.
                $value = htmlentities( $value );
                $xml->addChild( $key, $value );
            }
        }

        // pass back as XML
        //return $xml->asXML();

    // if you want the XML to be formatted, use the below instead to return the XML
        $doc = new DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML( $xml->asXML() );
        $doc->formatOutput = true;
        return $doc->saveXML();
    }


    /**
     * Convert an XML document to a multi dimensional array
     * Pass in an XML document (or SimpleXMLElement object) and this recrusively loops through and builds a representative array
     *
     * @param string $xml - XML document - can optionally be a SimpleXMLElement object
     * @return array ARRAY
     */
    public static function toArray( $xml ) {
        if ( is_string( $xml ) ) $xml = new SimpleXMLElement( $xml );
        $children = $xml->children();
        if ( !$children ) return (string) $xml;
        $arr = array();
        foreach ( $children as $key => $node ) {
            $node = ArrayToXML::toArray( $node );

            // support for 'anon' non-associative arrays
            if ( $key == 'anon' ) $key = count( $arr );

            // if the node is already set, put it into an array
            if ( isset( $arr[$key] ) ) {
                if ( !is_array( $arr[$key] ) || $arr[$key][0] == null ) $arr[$key] = array( $arr[$key] );
                $arr[$key][] = $node;
            } else {
                $arr[$key] = $node;
            }
        }
        return $arr;
    }

    // determine if a variable is an associative array
    public static function isAssoc( $array ) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
}
}