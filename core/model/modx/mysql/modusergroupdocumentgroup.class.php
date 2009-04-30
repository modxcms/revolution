<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modusergroupdocumentgroup.class.php');
class modUserGroupDocumentGroup_mysql extends modUserGroupDocumentGroup {
    function modUserGroupDocumentGroup_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}