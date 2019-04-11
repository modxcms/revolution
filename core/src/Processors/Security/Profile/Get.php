<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Profile;

use MODX\Revolution\modProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;

/**
 * Get a user profile
 * @param integer $id The ID of the user
 * @package MODX\Revolution\Processors\Security\Profile
 */
class Get extends modProcessor
{
    /** @var modUser $user */
    public $user;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('change_profile');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon('user_err_ns');
        }
        $this->user = $this->modx->getObject(modUser::class, $id);
        if (!$this->user) {
            return $this->modx->lexicon('user_err_not_found');
        }
        return true;
    }

    public function process()
    {
        /* if set, get groups for user */
        if ($this->getProperty('getGroups', false)) {
            $this->getUserGroups();
        }

        $userArray = $this->user->toArray();
        $profile = $this->user->getOne('Profile');
        if ($profile) {
            $userArray = array_merge($profile->toArray(), $userArray);
        }

        $userArray['dob'] = !empty($userArray['dob']) ? strftime('%m/%d/%Y', $userArray['dob']) : '';
        $userArray['blockeduntil'] = !empty($userArray['blockeduntil']) ? strftime('%m/%d/%Y %I:%M %p',
            $userArray['blockeduntil']) : '';
        $userArray['blockedafter'] = !empty($userArray['blockedafter']) ? strftime('%m/%d/%Y %I:%M %p',
            $userArray['blockedafter']) : '';
        $userArray['lastlogin'] = !empty($userArray['lastlogin']) ? strftime('%m/%d/%Y', $userArray['lastlogin']) : '';

        return $this->success('', $userArray);
    }

    /**
     * Get the User Groups for the user
     * @return array
     * @throws \xPDO\xPDOException
     */
    public function getUserGroups()
    {
        $c = $this->modx->newQuery(modUserGroupMember::class);
        $c->leftJoin(modUserGroupRole::class, 'UserGroupRole');
        $c->innerJoin(modUserGroup::class, 'UserGroup');
        $c->where(['member' => $this->user->get('id')]);
        $c->select($this->modx->getSelectColumns(modUserGroupMember::class, 'modUserGroupMember'));
        $c->select([
            'role_name' => 'UserGroupRole.name',
            'user_group_name' => 'UserGroup.name',
        ]);
        $members = $this->modx->getCollection(modUserGroupMember::class, $c);

        $data = [];
        /** @var modUserGroupMember $member */
        foreach ($members as $member) {
            $roleName = $member->get('role_name');
            if ($member->get('role') === 0) {
                $roleName = $this->modx->lexicon('none');
            }
            $data[] = [
                $member->get('user_group'),
                $member->get('user_group_name'),
                $member->get('member'),
                $member->get('role'),
                empty($roleName) ? '' : $roleName,
            ];
        }
        $this->user->set('groups', '(' . $this->modx->toJSON($data) . ')');

        return $data;
    }
}
