<?php
/**
 * Gets a role
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
class modUserGroupRoleGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modUserGroupRole';
    public $languageTopics = array('user');
    public $permission = 'view_role';
    public $objectType = 'role';
}
return 'modUserGroupRoleGetProcessor';