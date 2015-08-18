<?php
/**
 * Remove a Resource Group ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.category
 */

class modUserGroupAccessCategoryRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessCategory';
    public $objectType = 'access_category';
    public $languageTopics = array('access');
    public $permission = 'access_permissions';
}

return 'modUserGroupAccessCategoryRemoveProcessor';
