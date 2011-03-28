<?php
/**
 * Represents a MODX Resource that services JSON-RPC client requests.
 *
 * @package modx
 */
class modJSONRPCResource extends modResource {
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_fields['class_key']= 'modJSONRPCResource';
        $this->xpdo->setOption('modResponse.class','jsonrpc.modJSONRPCResponse');
    }

    public function process() {
        $this->xpdo->getResponse('jsonrpc.modJSONRPCResponse');
        parent :: process();
        return $this->_content;
    }
}
