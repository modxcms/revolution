<?php
/**
 * Extends modResource to service XML-RPC client requests.
 *
 * @package modx
 */
class modXMLRPCResource extends modResource {
    /**
     * @var array An array of services for this resource.
     * @access public
     */
    var $services= array ();

    /**#@+
     * Creates a modXMLRPCResource instance.
     *
     * {@inheritdoc}
     */
    function modXMLRPCResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    /** @ignore */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_fields['class_key']= 'modXMLRPCResource';
    }
    /**#@-*/

    /**
     * Overrides modResource::process to set the Response handler to
     * {@link modXMLRPCResponse}
     *
     * {@inheritdoc}
     */
    function process() {
        $this->xpdo->getResponse('xmlrpc.modXMLRPCResponse');
        parent :: process();
        return $this->_content;
    }
}
