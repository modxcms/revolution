<?php
/**
 * @package modx
 * @subpackage xmlrpc
 */
/**
 * Extends modResource to service XML-RPC client requests.
 *
 * @package modx
 * @subpackage xmlrpc
 */
class modXMLRPCResource extends modResource {
    /**
     * @var array An array of services for this resource.
     * @access public
     */
    public $services= array ();

    /**
     * Creates a modXMLRPCResource instance.
     *
     * {@inheritdoc}
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('class_key','modXMLRPCResource');
        $this->showInContextMenu = false;
    }

    /**
     * Overrides modResource::process to set the Response handler to
     * {@link modXMLRPCResponse}
     *
     * {@inheritdoc}
     */
    public function process() {
        $this->xpdo->getResponse('xmlrpc.modXMLRPCResponse');
        parent :: process();
        return $this->_content;
    }
}
