<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modusergroupmember.class.php');
class modUserGroupMember_mysql extends modUserGroupMember {
    function modUserGroupMember_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}