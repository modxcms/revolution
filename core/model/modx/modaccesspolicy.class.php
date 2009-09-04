<?php
/**
 * Defines criteria a principal must satisfy in order to access an object.
 *
 * @package modx
 */
class modAccessPolicy extends xPDOSimpleObject {
    function modAccessPolicy(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>