<?php
/**
 * @package modx
 * @subpackage mysql
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