<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
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
