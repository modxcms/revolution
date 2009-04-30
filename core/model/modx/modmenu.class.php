<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modMenu extends modAccessibleSimpleObject {
    function modMenu(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>