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
     * Creates an instance of the modTransportProvider class
     *
     * {@inheritdoc}
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
    }

    /**
     * Handles the response from the provider. Returns response in array format.
     *
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
        return $this->xpdo->rest->request($this->get('service_url'),$path,$method,$params);
    }

    /**
     * Grab all updates for a specific package
     *
     * @access public
     * @param modTransportPackage $package The package to grab updates for
     * @return array An array of available updates for the package
     */
    public function getUpdatesForPackage($package) {
        $updates = array ();
        $pa = $package->toArray();

        $this->getClient();

        $this->xpdo->getVersionData();
        $productVersion = $this->xpdo->version['code_name'].'-'.$this->xpdo->version['full_version'];

        $xml = $this->request('package/update','GET',array(
            'signature' => $package->get('signature'),
            'supports' => $productVersion,
        ));

        return $this->handleResponse($xml);
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
}