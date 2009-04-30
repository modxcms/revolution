<?php
/**
 * Represents a legacy webuser group/document group relationship.
 * 
 * @deprecated 2007-09-20 For migration purposes only.
 * @package modx
 */
class modWebGroupDocumentGroup extends xPDOSimpleObject {
    function modWebGroupDocumentGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>