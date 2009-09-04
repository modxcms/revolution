<?php
/**
 * @package modx
 */
class modAccessibleSimpleObject extends modAccessibleObject {
    function modAccessibleSimpleObject(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>