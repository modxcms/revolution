<?php
/**
 * @package modx
 */
class modTemplateVarResource extends xPDOSimpleObject {
    function modTemplateVarResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>