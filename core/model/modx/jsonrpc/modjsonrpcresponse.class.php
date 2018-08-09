<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once MODX_CORE_PATH . 'model/modx/xmlrpc/modxmlrpcresponse.class.php';
require_once MODX_CORE_PATH . 'model/modx/jsonrpc/jsonrpc.inc';
require_once MODX_CORE_PATH . 'model/modx/jsonrpc/jsonrpcs.inc';

/**
 * Extends modXMLRPCResponse to support servicing JSON-RPC client requests.
 *
 * @package modx
 * @subpackage jsonrpc
 */
class modJSONRPCResponse extends modXMLRPCResponse {
    /**
     * Output the content of this response
     * @param array $options An array of options for the output
     * @return void
     */
    public function outputContent(array $options= array()) {
        if (empty($options['rpc_type'])) $options['rpc_type']= 'JSON';
        parent :: outputContent($options);
    }

    /**
     * Load the JSON-RPC server
     * @param bool $execute Execute the server process
     * @return bool True if the server was successfully loaded
     */
    public function getServer($execute= false) {
        if ($this->server === null || !($this->server instanceof jsonrpc_server)) {
            $this->server= new jsonrpc_server($this->services, $execute);
        }
        return $this->server instanceof jsonrpc_server;
    }
}
