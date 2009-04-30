<?php
/**
 * Represents a system configuration setting.
 *
 * @package modx
 */
class modSystemSetting extends xPDOObject {
    function modSystemSetting(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>