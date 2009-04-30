<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modUserGroupRole extends xPDOSimpleObject {
    function modUserGroupRole(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>