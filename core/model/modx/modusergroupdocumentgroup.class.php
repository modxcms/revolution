<?php
/**
 * Represents a user group with access to a resource group.
 *
 * @package modx
 */
class modUserGroupDocumentGroup extends xPDOSimpleObject {
    function modUserGroupDocumentGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>