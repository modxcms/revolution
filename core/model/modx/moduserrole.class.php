<?php
/**
 * Represents a set of user permissions defining a role.
 *
 * @package modx
 */
class modUserRole extends xPDOSimpleObject {
    function modUserRole(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>