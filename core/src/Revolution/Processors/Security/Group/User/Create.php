<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group\User;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;

/**
 * Add a user to a user group
 * @param integer $usergroup The ID of the user group
 * @param integer $user The ID of the user
 * @param integer $role The ID of the role
 * @package MODX\Revolution\Processors\Security\Group\User
 */
class Create extends Processor
{
    /** @var modUser $user */
    public $user;

    /** @var modUserGroup $userGroup */
    public $userGroup;

    /** @var modUserGroupRole $role */
    public $role;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('usergroup_user_edit');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'user' => false,
            'usergroup' => false,
            'role' => false,
        ]);

        return true;
    }

    public function process()
    {
        $fields = $this->getProperties();
        if (!$this->validate($fields)) {
            return $this->failure();
        }

        /* create membership */
        /** @var modUserGroupMember $membership */
        $membership = $this->modx->newObject(modUserGroupMember::class);
        $membership->set('user_group', $this->userGroup->get('id'));
        $membership->set('member', $this->user->get('id'));
        $membership->set('role', $fields['role']);

        $rank = $this->getNewRank();
        $membership->set('rank', $rank);

        /* invoke OnUserBeforeAddToGroup event */
        $OnUserBeforeAddToGroup = $this->modx->invokeEvent('OnUserBeforeAddToGroup', [
            'user' => &$this->user,
            'usergroup' => &$this->userGroup,
            'membership' => &$membership,
        ]);
        $canSave = $this->processEventResponse($OnUserBeforeAddToGroup);

        if (!empty($canSave)) {
            return $this->failure($canSave);
        }

        /* save membership */
        if ($membership->save() === false) {
            return $this->failure($this->modx->lexicon('user_group_member_err_save'));
        }

        /* invoke OnUserAddToGroup event */
        $this->modx->invokeEvent('OnUserAddToGroup', [
            'user' => &$this->user,
            'usergroup' => &$this->userGroup,
            'membership' => &$membership,
        ]);

        /* set as primary group if the only group for user */
        if ($rank === 0) {
            $this->user->set('primary_group', $this->userGroup->get('id'));
            $this->user->save();
        }

        return $this->success('', $membership);
    }

    /**
     * @param array $fields
     * @return bool
     */
    public function validate(array $fields)
    {
        /* get user */
        if (empty($fields['user'])) {
            $this->addFieldError('user', $this->modx->lexicon('user_err_ns'));
        } else {
            $this->user = $this->modx->getObject(modUser::class, $fields['user']);
            if (!$this->user) {
                $this->addFieldError('user', $this->modx->lexicon('user_err_ns'));
            }
        }

        /* get user group */
        if (empty($fields['usergroup'])) {
            $this->addFieldError('user', $this->modx->lexicon('user_group_err_ns'));
        } else {
            $this->userGroup = $this->modx->getObject(modUserGroup::class, $fields['usergroup']);
            if (!$this->userGroup) {
                $this->addFieldError('user', $this->modx->lexicon('user_group_err_nf'));
            }
        }

        /* check role */
        if (!empty($fields['role'])) {
            $this->role = $this->modx->getObject(modUserGroupRole::class, $fields['role']);
            if (!$this->role) {
                $this->addFieldError('role', $this->modx->lexicon('role_err_nf'));
            }
        }

        if ($this->alreadyExists($fields)) {
            $this->addFieldError('user', $this->modx->lexicon('user_group_member_err_already_in'));
        }

        return !$this->hasErrors();
    }

    /**
     * @param array $fields
     * @return bool
     */
    public function alreadyExists(array $fields)
    {
        if (empty($fields['user']) || empty($fields['usergroup'])) {
            return false;
        }

        /* check to see if member is already in group */
        return $this->modx->getCount(modUserGroupMember::class, [
                'user_group' => $fields['usergroup'],
                'member' => $fields['user'],
            ]) > 0;
    }

    /**
     * @return int
     */
    public function getNewRank()
    {
        return $this->modx->getCount(modUserGroupMember::class, ['member' => $this->user->get('id')]);
    }
}
