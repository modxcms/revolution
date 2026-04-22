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
        $authority = (int)$this->getProperty('authority', 0);

        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('role_err_ns_name'));
        }
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('role_err_ae'));
        }
        if ($this->authorityExists($authority)) {
            $this->addFieldError('authority', $this->modx->lexicon('role_err_authority_exists'));
        }

        return parent::beforeSave();
    }

    /**
     * Check to see if a role already exists with the specified name
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount(
            modUserGroupRole::class,
            ['name' => $name, 'id:!=' => $this->getProperty('id')]
        ) > 0;
    }

    /**
     * Check whether the specified authority level already exists
     * @param int $authority
     * @return boolean
     */
    public function authorityExists(int $authority)
    {
        return $this->modx->getCount(
            modUserGroupRole::class,
            ['authority' => $authority, 'id:!=' => $this->getProperty('id')]
        ) > 0;
    }
}
