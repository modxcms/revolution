<?php
/**
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterQueue extends xPDOSimpleObject {
    function modDbRegisterQueue(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>