<?php
/**
 * modNamespace
 *
 * @package modx
 */
/**
 * Represents a Component in the MODx framework.
 *
 * @package modx
 */
class modNamespace extends xPDOObject {
    function modNamespace(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}