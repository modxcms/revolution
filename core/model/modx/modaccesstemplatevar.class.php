<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modAccessTemplateVar extends modAccessElement {
    function modAccessTemplateVar(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>