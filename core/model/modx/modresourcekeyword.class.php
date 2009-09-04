<?php
/**
 * @package modx
 * @deprecated 2.0.0
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