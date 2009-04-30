<?php
/**
 * Represents a user setting which overrides system and context settings.
 *
 * @package modx
 */
class modUserSetting extends xPDOObject {
    function modUserSetting(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>