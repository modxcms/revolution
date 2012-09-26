<?php
/**
 * Creates a role from a POST request.
 *
 * @package modx
 * @subpackage processors.security.role
 */
class modUserGroupRoleCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modUserGroupRole';
    public $languageTopics = array('user');
    public $permission = 'new_role';
    public $objectType = 'role';

    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('role_err_ns_name'));
        }

        if ($this->alreadyExists($name)) {
            $this->addFieldError('name',$this->modx->lexicon('role_err_ae'));
        }

        return parent::beforeSave();
    }

    /**
     * Check to see if a role already exists with the specified name
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('modUserGroupRole',array('name' => $name)) > 0;
    }
}
return 'modUserGroupRoleCreateProcessor';