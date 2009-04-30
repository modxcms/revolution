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
    var $client = null;

    function modTransportProvider(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
    }

    function handleResponse($response) {
        $result = array();
        if (!is_a($response, 'jsonrpcresp')) {
            $msg = $this->xpdo->lexicon('provider_err_no_response',array('provider' => $this->get('service_url')));
            $this->xpdo->log(MODX_LOG_LEVEL_ERROR, $msg);
            return $msg;
        }
        if ($response->faultCode()) {
            $msg = $this->xpdo->lexicon('provider_err_connect',array('error' => $response->faultString()));
            $this->xpdo->log(MODX_LOG_LEVEL_ERROR,$msg);
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
     * @return array A collection of metadata describing the packages.
     */
    function scanForPackages() {
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
     * @return array A collection of metadata describing the packages.
     */
    function scanForUpdates() {
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
    function getUpdatesForPackage($package) {
        $updates = array ();
        $pa = $package->toArray();

        $signature = new jsonrpcval($package->get('signature'),'string');
        $options = new jsonrpcval($this->xpdo->transport->config,'struct');
        $request = new jsonrpcmsg('modx.modx_update_package',array($signature,$options));
        $response = $this->sendRequest($request);

        return $this->handleResponse($response);
    }

    /**
     * Get the RPC client responsible for communicating with the provider.
     *
     * @return jsonrpc_client The JSON-RPC client instance.
     */
    function getClient() {
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
     * @param jsonrpcmsg $payload The JSON-RPC message to send.
     * @param integer $timeout The maximum number of seconds to wait for a server response.
     * @param string $protocol The protocol to use for the connection, http or https.
     * @return jsonrpcresp|boolean The JSON-RPC formatted response, or false on encountering some
     * errors.
     */
    function sendRequest($payload, $timeout = 0, $protocol = '') {
        $response = false;
        if ($this->getClient()) {
            $response = $this->client->send($payload, $timeout, $protocol);
        }
        return $response;
    }
}
?>