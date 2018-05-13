<?php

namespace MODX\Processors\Security\Role;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Update a role from a POST request
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modUserGroupRole';
    public $languageTopics = ['user'];
    public $permission = 'save_role';
    public $objectType = 'role';


    public function beforeSave()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('role_err_ns_name'));
        }

        return parent::beforeSave();
    }
}