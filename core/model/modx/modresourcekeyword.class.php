<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modResourceKeyword extends xPDOObject {
    function modResourceKeyword(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>