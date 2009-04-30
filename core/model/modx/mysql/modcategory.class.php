<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modcategory.class.php');
class modCategory_mysql extends modCategory {
    function modCategory_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}