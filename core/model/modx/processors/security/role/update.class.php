<?php
/**
 * Update a role from a POST request
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
class modUserGroupRoleUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modUserGroupRole';
    public $languageTopics = array('user');
    public $permission = 'save_role';
    public $objectType = 'role';

    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('role_err_ns_name'));
        }

        return parent::beforeSave();
    }
}
return 'modUserGroupRoleUpdateProcessor';