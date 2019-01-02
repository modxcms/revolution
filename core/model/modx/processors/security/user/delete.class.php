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
 * Deletes a user
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserDeleteProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modUser';
    public $languageTopics = array('user');
    public $permission = 'delete_user';
    public $objectType = 'user';
    public $beforeRemoveEvent = 'OnBeforeUserFormDelete';
    public $afterRemoveEvent = 'OnUserFormDelete';

    /** @var modUser $object */
    public $object;

    public function beforeRemove() {
        /* check if we are deleting our own record */
        if ($this->isSelf()) {
            return $this->modx->lexicon('user_err_cannot_delete_self');
        }

        /* ensure we cant delete last user in Administrator group */
        if ($this->isLastUserInAdministrators()) {
            return $this->modx->lexicon('user_err_cannot_delete_last_admin');
        }

        return parent::beforeRemove();
    }

    /**
     * See if the user is the active user
     * @return boolean
     */
    public function isSelf() {
        return $this->object->get('id') == $this->modx->user->get('id');
    }

    /**
     * See if the user is the last member in the administrators group
     * @return boolean
     */
    public function isLastUserInAdministrators() {
        $last = false;
        /** @var modUserGroup $group */
        $group = $this->modx->getObject('modUserGroup',array('name' => 'Administrator'));
        if ($group && $this->object->isMember('Administrator')) {
            $numberInAdminGroup = $this->modx->getCount('modUserGroupMember',array(
                'user_group' => $group->get('id'),
            ));
            if ($numberInAdminGroup <= 1) {
                $last = true;
            }
        }
        return $last;
    }
}
return 'modUserDeleteProcessor';
