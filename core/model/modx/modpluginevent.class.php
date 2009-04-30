<?php
/**
 * Represents a plugin registered for a specific event.
 *
 * @package modx
 */
class modPluginEvent extends xPDOObject {
    function modPluginEvent(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>