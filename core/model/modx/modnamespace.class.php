<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modNamespace extends xPDOObject {
    function modNamespace(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>