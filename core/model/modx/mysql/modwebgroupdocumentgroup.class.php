<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modwebgroupdocumentgroup.class.php');
class modWebGroupDocumentGroup_mysql extends modWebGroupDocumentGroup {
    function modWebGroupDocumentGroup_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}