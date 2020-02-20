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
 * Add a user to a user group
 *
 * @param integer $usergroup The ID of the user group
 * @param integer $user The ID of the user
 * @param integer $role The ID of the role
 *
 * @var modX $this->modx
 * @var modProcessor $this
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modSecurityGroupUserCreateProcessor extends modProcessor {
    /** @var modUser $user */
    public $user;
    /** @var modUserGroup $userGroup */
    public $userGroup;
    /** @var modUserGroupRole $role */
    public $role;

    public function checkPermissions() {
        return $this->modx->hasPermission('usergroup_user_edit');
    }
    public function getLanguageTopics() {
        return array('user');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'user' => false,
            'usergroup' => false,
            'role' => false,
        ));
        return true;
    }

    public function process() {
        $fields = $this->getProperties();
        if (!$this->validate($fields)) {
            return $this->failure();
        }

        /* create membership */
        /** @var modUserGroupMember $membership */
        $membership = $this->modx->newObject('modUserGroupMember');
        $membership->set('user_group',$this->userGroup->get('id'));
        $membership->set('member',$this->user->get('id'));
        $membership->set('role',$fields['role']);

        $rank = $this->getNewRank();
        $membership->set('rank',$rank);

        /* invoke OnUserBeforeAddToGroup event */
        $OnUserBeforeAddToGroup = $this->modx->invokeEvent('OnUserBeforeAddToGroup', array(
            'user' => &$this->user,
            'usergroup' => &$this->userGroup,
            'membership' => &$membership,
        ));
        $canSave = $this->processEventResponse($OnUserBeforeAddToGroup);

        if (!empty($canSave)) {
            return $this->failure($canSave);
        }

        /* save membership */
        if ($membership->save() == false) {
            return $this->failure($this->modx->lexicon('user_group_member_err_save'));
        }

        /* invoke OnUserAddToGroup event */
        $this->modx->invokeEvent('OnUserAddToGroup', array(
            'user' => &$this->user,
            'usergroup' => &$this->userGroup,
            'membership' => &$membership,
        ));

        /* set as primary group if the only group for user */
        if ($rank == 0) {
            $this->user->set('primary_group',$this->userGroup->get('id'));
            $this->user->save();
        }

        return $this->success('',$membership);
    }

    public function validate(array $fields) {
        /* get user */
        if (empty($fields['user'])) {
            $this->addFieldError('user',$this->modx->lexicon('user_err_ns'));
        } else {
            $this->user = $this->modx->getObject('modUser',$fields['user']);
            if (!$this->user) $this->addFieldError('user',$this->modx->lexicon('user_err_ns'));
        }

        /* get usergroup */
        if (empty($fields['usergroup'])) {
            $this->addFieldError('user',$this->modx->lexicon('user_group_err_ns'));
        } else {
            $this->userGroup = $this->modx->getObject('modUserGroup',$fields['usergroup']);
            if (!$this->userGroup) $this->addFieldError('user',$this->modx->lexicon('user_group_err_nf'));
        }

        /* check role */
        if (!empty($fields['role'])) {
            $this->role = $this->modx->getObject('modUserGroupRole',$fields['role']);
            if (!$this->role) $this->addFieldError('role',$this->modx->lexicon('role_err_nf'));
        }

        if ($this->alreadyExists($fields)) {
            $this->addFieldError('user',$this->modx->lexicon('user_group_member_err_already_in'));
        }

        return !$this->hasErrors();
    }

    public function alreadyExists(array $fields) {
        if (empty($fields['user']) || empty($fields['usergroup'])) return false;

        /* check to see if member is already in group */
        return $this->modx->getCount('modUserGroupMember',array(
            'user_group' => $fields['usergroup'],
            'member' => $fields['user'],
        )) > 0;
    }

    public function getNewRank() {
        return $this->modx->getCount('modUserGroupMember',array('member' => $this->user->get('id')));
    }
}
return 'modSecurityGroupUserCreateProcessor';
