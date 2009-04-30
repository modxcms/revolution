<?php
/**
 * Represents a user membership in a user group.
 *
 * @package modx
 */
class modUserGroupMember extends xPDOSimpleObject {
    function modUserGroupMember(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>