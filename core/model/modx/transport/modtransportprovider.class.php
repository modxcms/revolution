<?php
/**
 * @package modx
 * @subpackage transport
 */

require_once MODX_CORE_PATH . 'model/modx/xmlrpc/xmlrpc.inc';
require_once MODX_CORE_PATH . 'model/modx/jsonrpc/jsonrpc.inc';

/**
 * Represents a remote transport package provider service.
 *
 * @uses jsonrpc_client This JSON-RPC implementation is used to communicate with a remote server
 * that can provide information about and downloads of one or more MODx transport packages.
 */
class modTransportProvider extends xPDOSimpleObject {
    /**
     * The JSON-RPC client instance that will communicate with the server.
     * @var jsonrpc_client
     */
    public $client = null;

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
        $result = array();
        if (!($response instanceof jsonrpcresp)) {
            $msg = $this->xpdo->lexicon('provider_err_no_response',array('provider' => $this->get('service_url')));
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $msg);
            return $msg;
        }
        if ($response->faultCode()) {
            $msg = $this->xpdo->lexicon('provider_err_connect',array('error' => $response->faultString()));
            $this->xpdo->log(modX::LOG_LEVEL_ERROR,$msg);
            return $msg;
        }
        elseif ($value = $response->value()) {
            if (is_array($value)) {
                $result = $value;
            }
        }
        return $result;
    }

    /**
     * Scan the provider for new packages available for installation.
     *
     * @access public
     * @return array A collection of metadata describing the packages.
     */
    public function scanForPackages() {
        $packages = array ();
        $installed = array ();
        if ($installedPkgs = $this->getMany('Packages')) {
            foreach ($installedPkgs as $iPkg) {
                $installed[] = $iPkg->get('signature');
            }
        }
        $installed = implode(',',$installed);
        $installed = new jsonrpcval($installed,'string');
        $cfg = isset($this->xpdo->transport)
            ? $this->xpdo->transport->config
            : array();
        $options = new jsonrpcval($cfg, 'struct');
        $request = new jsonrpcmsg('modx.modx_get_repositories');
        $request->addParam($options);
        $request->addParam($installed);
        $response = $this->sendRequest($request);
        return $this->handleResponse($response);
    }

    /**
     * Scan the provider for available updates to existing packages installed in the workspace.
     *
     * @access public
     * @return array A collection of metadata describing the packages.
     */
    public function scanForUpdates() {
        $updates = array ();
        $installed = array ();
        if ($installedPkgs = $this->getMany('Packages')) {
            foreach ($installedPkgs as $iPkg) {
                $installed[] = new jsonrpcval($iPkg->toArray(), 'struct');
            }
        }
        $options = new jsonrpcval($this->xpdo->transport->config, 'struct');
        $request = new jsonrpcmsg('modx.modx_update_all_packages');
        $request->addParam($installed);
        $request->addParam($options);
        $response = $this->sendRequest($request);

        return $this->handleResponse($response);
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


        $this->xpdo->getVersionData();
        $productVersion = $this->xpdo->version['code_name'].'-'.$this->xpdo->version['full_version'];

        $signature = new jsonrpcval($package->get('signature'),'string');
        $productVersion = new jsonrpcval($productVersion,'string');
        $options = new jsonrpcval($this->xpdo->transport->config,'struct');
        $request = new jsonrpcmsg('modx.modx_update_version',array(
            $signature,
            $productVersion,
            $options,
        ));
        $response = $this->sendRequest($request);

        return $this->handleResponse($response);
    }

    /**
     * Get the RPC client responsible for communicating with the provider.
     *
     * @access public
     * @return jsonrpc_client The JSON-RPC client instance.
     */
    public function getClient() {
        if ($this->client == null) {
            if ($url = $this->get('service_url')) {
                $this->client = new jsonrpc_client($url);
                $this->client->return_type = 'phpvals';
            }
        }
        return $this->client;
    }

    /**
     * Send a request to the provider service and get the response.
     *
     * @access public
     * @param jsonrpcmsg $payload The JSON-RPC message to send.
     * @param integer $timeout The maximum number of seconds to wait for a server response.
     * @param string $protocol The protocol to use for the connection, http or https.
     * @return jsonrpcresp|boolean The JSON-RPC formatted response, or false on encountering some
     * errors.
     */
    public function sendRequest($payload, $timeout = 0, $protocol = '') {
        $response = false;
        if ($this->getClient()) {
            $response = $this->client->send($payload, $timeout, $protocol);
        }
        return $response;
    }
}