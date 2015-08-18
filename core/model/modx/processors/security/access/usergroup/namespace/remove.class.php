<?php
/**
 * Remove a Resource Group ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.category
 */

class modUserGroupAccessNamespaceRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessNamespace';
    public $objectType = 'access_namespace';
    public $languageTopics = array('access');
    public $permission = 'access_permissions';
}

return 'modUserGroupAccessNamespaceRemoveProcessor';
