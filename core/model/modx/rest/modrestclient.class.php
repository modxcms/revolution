<?php
/**
 * @package modx
 */
class modRestClient {

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            'port' => 80,
        ),$config);
    }

    public function getConnection() {
        if (function_exists('curl_init')) {
            $className = $this->modx->loadClass('rest.modRestCurlClient','',false,true);
        } else {
            $className = $this->modx->loadClass('rest.modRestSockClient','',false,true);
        }

        if ($className) {
            $this->conn = new $className($this->modx,$this->config);
        }
        return is_object($this->conn);
    }

    public function request($host,$path,$method = 'GET',$params = array()) {
        if (!is_object($this->conn)) {
            $loaded = $this->getConnection();
            if (!$loaded) return false;
        }
        return $this->conn->request($host,$path,$method,$params);
    }
}