<?php
/**
 * @package modx
 * @subpackage transport
 */
/**
 * Represents a remote transport package provider service.
 *
 * @uses modRestClient This REST implementation is used to communicate with a
 * remote server that can provide information about and downloads of one or more
 * MODx transport packages.
 */
class modTransportProvider extends xPDOSimpleObject {
    /**
     * Handles the response from the provider. Returns response in array format.
     *
     * @deprecated 2.0.0-rc1 - Jan 14, 2010
     * @access public
     * @param jsonrpcresp $response The json-rpc response.
     * @return array The parsed response.
     */
    public function handleResponse($response) {
        $sxml = simplexml_load_string($response);
        if (!$sxml) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not connect to provider at: '.$this->get('service_url'));
            return false;
        }
        return $sxml;
    }

    /**
     * Sends a REST request to the provider
     *
     * @param string $path
     * @param string $method
     * @param array $params
     */
    public function request($path,$method = 'GET',$params = array()) {
        if ($this->xpdo->rest == null) $this->getClient();
        $params = array_merge(array(
            'api_key' => $this->get('api_key'),
        ),$params);
        return $this->xpdo->rest->request($this->get('service_url'),$path,$method,$params);
    }

    /**
     * Get the client responsible for communicating with the provider.
     *
     * @access public
     * @return jsonrpc_client The JSON-RPC client instance.
     */
    public function getClient() {
        if (empty($this->xpdo->rest)) {
            $this->xpdo->getService('rest','rest.modRestClient');
            $loaded = $this->xpdo->rest->getConnection();
            if (!$loaded) return false;
        }
        return $this->xpdo->rest;
    }

    /**
     * Verifies the authenticity of the provider
     *
     * @access public
     * @return boolean True if verified, xml if failed
     */
    public function verify() {
        $response = $this->request('verify','GET');
        if ($response->isError()) {
            $message = $response->getError();
            if ($this->xpdo->lexicon && $this->xpdo->lexicon->exists('provider_err_'.$message)) {
                $message = $this->xpdo->lexicon('provider_err_'.$message);
            }
            return $message;
        }
        $status = $response->toXml();
        return (boolean)$status->verified;
    }

    /**
     * Overrides xPDOObject::save to set the createdon date.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag= null) {
        if ($this->isNew() && !$this->get('created')) {
            $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }
}