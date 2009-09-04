<?php
/**
 * Restricts or grants access to certain functionality. Grouped by Access
 * Policy.
 *
 * @package modx
 */
class modAccessPermission extends xPDOSimpleObject {
    function modAccessPermission(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}