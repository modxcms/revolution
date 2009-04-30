<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modweblink.class.php');
class modWebLink_mysql extends modWebLink {
    function modWebLink_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}