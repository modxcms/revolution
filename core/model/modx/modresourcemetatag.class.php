<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modResourceMetatag extends xPDOObject {
    function modResourceMetatag(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>