<?php
/**
 * Remove a context ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.usergroup
 */

class modUserGroupAccessContextRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessContext';
    public $objectType = 'access_context';
    public $languageTopics = array('access');
    public $permission = 'access_permissions';
}

return 'modUserGroupAccessContextRemoveProcessor';
