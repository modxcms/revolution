<?php
/**
 * Remove a Resource Group ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.resourcegroup
 */
class modUserGroupAccessResourceGroupRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessResourceGroup';
    public $objectType = 'access_rgroup';
    public $languageTopics = array('access');
    public $permission = 'access_permissions';
}

return 'modUserGroupAccessResourceGroupRemoveProcessor';