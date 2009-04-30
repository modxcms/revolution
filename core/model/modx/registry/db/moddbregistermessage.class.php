<?php
/**
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterMessage extends xPDOObject {
    function modDbRegisterMessage(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>