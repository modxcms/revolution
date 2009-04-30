<?php
/**
 * Extends modResource to service XML-RPC client requests.
 * 
 * @package modx
 */
class modXMLRPCResource extends modResource {
    var $services= array ();

    function modXMLRPCResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_fields['class_key']= 'modXMLRPCResource';
    }

    function process() {
        $this->xpdo->getResponse('xmlrpc.modXMLRPCResponse');
        parent :: process();
        return $this->_content;
    }
}
