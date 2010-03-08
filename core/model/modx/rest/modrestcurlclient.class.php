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
    /**
     * Extends modRestClient::request to provide cURL specific request handling
     *
     * {@inheritdoc}
     */
    public function request($host,$path,$method = 'GET',$params = array()) {
        $q = http_build_query($params);
        $ch = curl_init();

        switch ($method) {
            case 'GET':
                $path .= '?'.$q;
                break;
            case 'POST':
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
                break;
        }
        $url = str_replace('&amp;', '&', $host.$path);
        curl_setopt($ch, CURLOPT_URL,$url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT,30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = trim(curl_exec($ch));
        curl_close($ch);

        return $result;
    }
}