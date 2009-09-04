<?php
/**
 * @package modx
 */
class modAccessElement extends modAccess {
    function modAccessElement(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>