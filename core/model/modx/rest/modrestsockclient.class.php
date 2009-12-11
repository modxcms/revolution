<?php
require_once dirname(__FILE__) . '/modrestclient.class.php';

class modRestSockClient extends modRestClient {



    public function request($host,$path,$method = 'GET',$params = array()) {
        $method = strtoupper($method);
        $purl = parse_url($host);
        $host = $purl['host'];
        $purl['path'] = !empty($purl['path']) ? $purl['path'] : '/';
        $purl['port'] = !empty($purl['port']) ? $purl['port'] : '80';

        $sock = @fsockopen($purl['host'], $purl['port'],$errno,$errstr,10);

        /* setup params */
        $q = http_build_query($params);
        if ($method == "GET") {
            $path .= "?" . $q;
        }

        $out = $method." ".$purl['path']."/$path HTTP/1.1\r\n"
                ."Host: $host\r\n"
                ."User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n"
                ."Accept: */*\r\n"
                ."Accept-Language: en-us,en;q=0.5\r\n"
                ."Accept-Charset: utf-8;q=0.7,*;q=0.7\r\n"
                /*."Keep-Alive: 300\r\n" */
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

        $response = trim($response);
        $response = rtrim($response,'0');
        $response = rtrim($response);
        $startPos = strpos($response, "\r\n\r\n");
        $response = substr($response, $startPos+8);
        return $response;
    }

}
