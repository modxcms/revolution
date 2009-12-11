<?php
require_once dirname(__FILE__) . '/modrestclient.class.php';

class modRestCurlClient extends modRestClient {

    public function request($host,$path,$method = 'GET',$params = array()) {
        $q = http_build_query($params);
        $ch = curl_init();

        switch ($method) {
            case 'GET':
                $path .= '?'.$q;
                break;
            case 'POST':
                curl_setopt($ch,CURLOPT_POST);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
                break;
        }
        curl_setopt($ch, CURLOPT_URL, $host.$path);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT,120);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = trim(curl_exec($ch));
        curl_close($ch);

        return $result;
    }
}