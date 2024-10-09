<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User;

use MODX\Revolution\Formatter\modManagerDateFormatter;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;
use MODX\Revolution\Processors\Model\GetProcessor;

/**
 * Get a user
 * @param integer $id The ID of the user
 * @package MODX\Revolution\Processors\Security\User
 */
class Get extends GetProcessor
{
    public $classKey = modUser::class;
    public $languageTopics = ['user'];
    public $permission = 'view_user';
    public $objectType = 'user';

    private modManagerDateFormatter $formatter;

    public function initialize()
    {
        $this->formatter = $this->modx->services->get(modManagerDateFormatter::class);
        return parent::initialize();
    }

    /**
     * @throws \xPDO\xPDOException
     */
    public function beforeOutput()
    {
        if ($this->getProperty('getGroups', false)) {
            $this->getGroups();
        }
        parent::beforeOutput();
    }

    /**
     * Get all the groups for the user
     * @return array
     * @throws \xPDO\xPDOException
     */
    public function getGroups()
    {
        $c = $this->modx->newQuery(modUserGroupMember::class);
        $c->select($this->modx->getSelectColumns(modUserGroupMember::class, 'modUserGroupMember'));
        $c->select([
            'role_name' => 'UserGroupRole.name',
            'user_group_name' => 'UserGroup.name',
            'user_group_desc' => 'UserGroup.description',
        ]);
        $c->leftJoin(modUserGroupRole::class, 'UserGroupRole');
        $c->innerJoin(modUserGroup::class, 'UserGroup');
        $c->where(['member' => $this->object->get('id')]);
        $c->sortby('modUserGroupMember.rank');
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
                htmlentities($member->get('user_group_name'), ENT_QUOTES, 'UTF-8'),
                $member->get('member'),
                $member->get('role'),
                empty($roleName) ? '' : $roleName,
                $this->object->get('primary_group') === $member->get('user_group'),
                $member->get('rank'),
                $member->get('user_group_desc'),
            ];
        }
        $this->object->set('groups', '(' . $this->modx->toJSON($data) . ')');

        return $data;
    }

    public function cleanup()
    {
        $userArray = $this->object->toArray();

        $profile = $this->object->getOne('Profile');
        if ($profile) {
            $userArray = array_merge($profile->toArray(), $userArray);
        }

        $userArray['dob'] = !empty($userArray['dob']) ? date('Y-m-d', $userArray['dob']) : '';
        $userArray['blockeduntil'] = $this->formatter->formatHidden($userArray['blockeduntil']);
        $userArray['blockedafter'] = $this->formatter->formatHidden($userArray['blockedafter']);
        $userArray['createdon'] = $this->formatter->formatDateTime($userArray['createdon']);
        $userArray['lastlogin'] = !empty($userArray['lastlogin']) ? $this->formatter->formatDateTime($userArray['lastlogin']) : '';

        unset($userArray['password'], $userArray['cachepwd'], $userArray['sessionid'], $userArray['salt']);
        return $this->success('', $userArray);
    }
}
