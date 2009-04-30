<?php
/**
 * Represents a legacy webuser/group relationship.
 * 
 * @deprecated 2007-09-20 For migration purposes only.
 * @package modx
 */
class modWebGroupMember extends xPDOSimpleObject {
    function modWebGroupMember(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>