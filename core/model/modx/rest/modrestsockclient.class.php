<?php
/**
 * @package modx
 * @subpackage rest
 */
require_once dirname(__FILE__) . '/modrestclient.class.php';
/**
 *
 * @deprecated To be removed in 2.3. See modRest instead.
 *
 * @package modx
 * @subpackage rest
 */
class modRestSockClient extends modRestClient {
    /**
     * Extends modRestClient::request to provide socket-specific request
     * handling
     *
     * @todo Ensure this strips whitespace that prevents this class from working
     *
     * @param string $host The host of the REST server.
     * @param string $path The path to request to on the REST server.
     * @param string $method The HTTP method to use for the request. May be GET,
     * PUT or POST.
     * @param array $params An array of parameters to send with the request.
     * @param array $options An array of options to pass to the request.
     * @return modRestResponse The response object.
     */
    public function request($host,$path,$method = 'GET',array $params = array(),array $options = array()) {
        $method = strtoupper($method);
        $purl = parse_url($host);
        $host = $purl['host'];
        $purl['path'] = !empty($purl['path']) ? $purl['path'] : $this->config[modRestClient::OPT_PATH];
        $purl['port'] = !empty($purl['port']) ? $purl['port'] : $this->config[modRestClient::OPT_PORT];

        $sock = @fsockopen($purl['host'], $purl['port'],$errno,$errstr,30);

        /* setup params */
        $q = http_build_query($params);
        if ($method == "GET") {
            $path .= "?" . $q;
        }
        $ip = $this->modx->request->getClientIp();
        $ip = $ip['ip'];

        $out = $method." ".$purl['path']."/$path ".$_SERVER['SERVER_PROTOCOL']."\r\n"
                ."Host: $host\r\n"
                ."User-Agent: ".$this->config[modRestClient::OPT_USERAGENT]."\r\n"
                ."Content-type: text/xml; charset=UTF-8\r\n"
                ."Accept: */*\r\n"
                ."Accept-Language: en-us,en;q=0.5\r\n"
                ."Accept-Charset: utf-8;q=0.7,*;q=0.7\r\n"
                ."Accept-Encoding: gzip, deflate, compress;q=0.9\r\n"
                /*."Keep-Alive: 300\r\n" */
                ."Referer: ".$ip."\r\n"
                ."Connection: Close\r\n\r\n";

        fwrite($sock,$out);

        if ($method == 'POST') {
            fwrite($sock, "Content-length: ".strlen($q)."\r\n");
            fwrite($sock, $q);
        }

        $response = '';
        $i = 0;
        while ($line = fread($sock, 4096)) {
            if ($i > 20) break;
            $response .= $line;
            $i++;
        }
        fclose($sock);

        list($header,$response) = explode('<?xml',$response);
        $response = '<?xml'.$response;

        /* strip junk at end of string */
        $response = strrev($response);
        $response = strrev(substr($response,strpos($response,'>')));

        /* commented out for debugging */
        //echo '<textarea cols="180" rows="50">'.$response.'</textarea>'; die();
        //echo '<pre>'.htmlentities($xml->asXml()).'</pre>'; die();
        return $response;
    }

}
