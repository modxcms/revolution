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
 * Represents a MODX Resource that services JSON-RPC client requests.
 *
 * @package modx
 * @subpackage jsonrpc
 */
class modJSONRPCResource extends modResource {
	public $allowListingInClassKeyDropdown = false;
    /**
     * Overrides the modResource constructor to set the response class and class_key for this Resource type
     * @param xPDO $xpdo
     */
    function __construct(xPDO &$xpdo) {
        parent :: __construct($xpdo);
        $this->_fields['class_key']= 'modJSONRPCResource';
        $this->xpdo->setOption('modResponse.class','jsonrpc.modJSONRPCResponse');
        $this->showInContextMenu = false;
    }

    /**
     * Overrides modResource::process to provide a custom response
     *
     * @see modResource::process()
     * @return string The processed content
     */
    public function process() {
        $this->xpdo->getResponse('jsonrpc.modJSONRPCResponse');
        parent :: process();
        return $this->_content;
    }
}
