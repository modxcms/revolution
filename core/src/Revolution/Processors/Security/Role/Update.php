<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Role;


use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modUserGroupRole;

/**
 * Update a role from a POST request
 * @param integer $id The ID of the role
 * @package MODX\Revolution\Processors\Security\Role
 */
class Update extends UpdateProcessor
{
    public $classKey = modUserGroupRole::class;
    public $languageTopics = ['user'];
    public $permission = 'save_role';
    public $objectType = 'role';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('role_err_ns_name'));
        }

        return parent::beforeSave();
    }
}
