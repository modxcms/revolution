<?php
/**
 * Represents a legacy webuser_group entity.
 * 
 * @deprecated 2007-09-20 For migration purposes only.
 * @package modx
 */
class modWebGroup extends xPDOSimpleObject {
    function modWebGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>