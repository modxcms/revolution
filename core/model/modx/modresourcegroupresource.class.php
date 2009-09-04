<?php
/**
 * @package modx
 */
class modResourceGroupResource extends xPDOSimpleObject {
    function modResourceGroupResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>