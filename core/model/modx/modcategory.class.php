<?php
/**
 * Represents a category for organizing modElement instances.
 *
 * @package modx
 */
class modCategory extends xPDOSimpleObject {
    function modCategory(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>