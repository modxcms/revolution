<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Removes a role.
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
class modUserGroupRoleRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modUserGroupRole';
    public $languageTopics = array('user');
    public $permission = 'delete_role';
    public $objectType = 'role';

    public function beforeRemove() {
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
     *
     * @TODO: when this is converted in build script, convert to i18n
     *
     * @return boolean
     */
    public function isCoreRole() {
        return $this->object->get('name') == 'Member' || $this->object->get('name') == 'Super User';
    }

    /**
     * See if the Role is assigned to any users
     *
     * @return boolean
     */
    public function isAssigned() {
        $c = $this->modx->newQuery('modUserGroupMember');
        $c = $c->where(array('role' => $this->object->get('id')));
        return $this->modx->getCount('modUserGroupMember',$c) > 0;
    }
}
return 'modUserGroupRoleRemoveProcessor';
