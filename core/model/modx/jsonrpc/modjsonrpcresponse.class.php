<?php
require_once(MODX_CORE_PATH . 'model/modx/xmlrpc/modxmlrpcresponse.class.php');
require_once(MODX_CORE_PATH . 'model/modx/jsonrpc/jsonrpc.inc');
require_once(MODX_CORE_PATH . 'model/modx/jsonrpc/jsonrpcs.inc');

/**
 * Extends modXMLRPCResponse to support servicing JSON-RPC client requests.
 *
 * @package modx
 * @subpackage jsonrpc
 */
class modJSONRPCResponse extends modXMLRPCResponse {
    function modJSONRPCResponse(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
    }

    function outputContent($options= array()) {
        if (!isset($options['rpc_type'])) $options['rpc_type']= 'JSON';

        parent :: outputContent($options);
    }

    function getServer($execute= false) {
        if ($this->server === null || !is_a($this->server, 'jsonrpc_server')) {
            $this->server= new jsonrpc_server($this->services, $execute);
        }
        return is_a($this->server, 'jsonrpc_server');
    }
}