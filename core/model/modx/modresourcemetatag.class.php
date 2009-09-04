<?php
/**
 * @package modx
 * @deprecated 2.0.0
 */
class modResourceMetatag extends xPDOObject {
    function modResourceMetatag(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}