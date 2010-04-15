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
class modRestSockClient extends modRestClient {
    /**
     * Extends modRestClient::request to provide socket-specific request
     * handling
     *
     * {@inheritdoc}
     */
    public function request($host,$path,$method = 'GET',$params = array()) {
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

        $out = $method." ".$purl['path']."/$path HTTP/1.1\r\n"
                ."Host: $host\r\n"
                ."User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n"
                ."Content-type: text/xml; charset=UTF-8\r\n"
                ."Accept: */*\r\n"
                ."Accept-Language: en-us,en;q=0.5\r\n"
                ."Accept-Charset: utf-8;q=0.7,*;q=0.7\r\n"
                ."Accept-Encoding: gzip, deflate, compress;q=0.9\r\n"
                /*."Keep-Alive: 300\r\n" */
                ."Referer: ".$_SERVER['REMOTE_ADDR']."\r\n"
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
        /* commented out for debugging */
        //echo '<textarea cols="180" rows="50">'.$response.'</textarea>'; die();
        //echo '<pre>'.htmlentities($xml->asXml()).'</pre>'; die();
        return $response;
    }

}
