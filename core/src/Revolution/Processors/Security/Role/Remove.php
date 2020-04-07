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


use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;

/**
 * Removes a role.
 * @param integer $id The ID of the role
 * @package MODX\Revolution\Processors\Security\Role
 */
class Remove extends RemoveProcessor
{
    public $classKey = modUserGroupRole::class;
    public $languageTopics = ['user'];
    public $permission = 'delete_role';
    public $objectType = 'role';

    /**
     * @return bool|string|null
     */
    public function beforeRemove()
    {
        if ($this->isCoreRole()) {
            return $this->modx->lexicon('role_err_remove_admin');
        }

        /* don't delete if this role is assigned */
        if ($this->isAssigned()) {
            return $this->modx->lexicon('role_err_has_users');
        }

        return parent::beforeRemove();
    }

    /**
     * Don't delete the Member or Super User roles.
     * @TODO: when this is converted in build script, convert to i18n
     * @return boolean
     */
    public function isCoreRole()
    {
        return $this->object->get('name') === 'Member' || $this->object->get('name') === 'Super User';
    }

    /**
     * See if the Role is assigned to any users
     * @return boolean
     */
    public function isAssigned()
    {
        $c = $this->modx->newQuery(modUserGroupMember::class);
        $c = $c->where(['role' => $this->object->get('id')]);

        return $this->modx->getCount(modUserGroupMember::class, $c) > 0;
    }
}
