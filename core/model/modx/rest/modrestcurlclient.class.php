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
    const OPT_USERAGENT = 'userAgent';

    function __construct(modX &$modx,array $config = array()) {
        parent::__construct($modx, $config);
        $this->config = array_merge(array(
            'userAgent' => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)"
        ),$this->config);
    }
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
        curl_setopt($ch, CURLOPT_TIMEOUT,$this->config[modRestClient::OPT_TIMEOUT]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT,$this->config[modRestCurlClient::OPT_USERAGENT]);

        /* if proxy is set, attempt to use it */
        $proxyHost = $this->modx->getOption('proxy_host',null,'');
        if (!empty($proxyHost)) {
            curl_setopt($ch, CURLOPT_PROXY,$proxyHost);
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

        /* can only use follow location if safe_mode and open_basedir are off */
        $safeMode = ini_get('safe_mode');
        $openBasedir = ini_get('open_basedir');
        if (empty($safeMode) && empty($openBasedir)) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }

        $result = trim(curl_exec($ch));
        curl_close($ch);

        return $result;
    }
}