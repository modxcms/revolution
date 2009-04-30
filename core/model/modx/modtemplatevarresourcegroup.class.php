<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modTemplateVarResourceGroup extends xPDOSimpleObject {
    function modTemplateVarResourceGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>