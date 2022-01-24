<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group;

use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;

/**
 * Update a user group
 * @param integer $id The ID of the user group
 * @param string $name The new name of the user group
 * @package MODX\Revolution\Processors\Security\Group
 */
class Update extends UpdateProcessor
{
    public $classKey = modUserGroup::class;
    public $languageTopics = ['user'];
    public $permission = 'usergroup_save';
    public $objectType = 'user_group';
    public $beforeSaveEvent = 'OnUserGroupBeforeFormSave';
    public $afterSaveEvent = 'OnUserGroupFormSave';

    /**
     * Override the modObjectUpdateProcessor::initialize method to allow for grabbing the (anonymous) user group
     * @return boolean|string
     */
    public function initialize()
    {
        $id = $this->getProperty('id', false);
        if (empty($id)) {
            $this->object = $this->modx->newObject(modUserGroup::class);
            $this->object->set('id', 0);
        } else {
            $this->object = $this->modx->getObject(modUserGroup::class, $id);
            if ($this->object === null) {
                return $this->modx->lexicon('user_group_err_not_found');
            }
        }

        return true;
    }

    /**
     * Override the saveObject method to prevent saving of the (anonymous) group
     * {@inheritDoc}
     * @return boolean
     */
    public function saveObject()
    {
        $saved = true;
        if (!in_array($this->getProperty('id'), ['0', 0, null])) {
            $saved = $this->object->save();
        }

        return $saved;
    }

    /**
     * @return mixed
     */
    public function beforeSave()
    {
        $c = $this->modx->newQuery(modUserGroup::class);
        $c->where([
            'id:!=' => $this->object->get('id'),
            'name' => $this->getProperty('name')
        ]);

        $count = $this->modx->getCount(modUserGroup::class, $c);
        if ($count > 0) {
            return $this->modx->lexicon('user_group_err_already_exists');
        }

        if ($this->isAdminGroup()) {
            $this->object->set('parent', 0);
        }

        return parent::beforeSave();
    }

    /**
     * @return mixed
     */
    public function afterSave()
    {
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->addUsers();
        }

        return parent::afterSave();
    }

    /**
     * Add users to the User Group
     * @return modUserGroupMember[]
     */
    public function addUsers()
    {
        $users = $this->getProperty('users');
        $id = $this->getProperty('id');
        $memberships = [];
        $flush = false;

        if ($users !== null && !empty($id)) {
            $users = is_array($users) ? $users : json_decode($users, true);

            $currentUsers = [];
            $currentUserIds = [];
            foreach ($users as $user) {
                $currentUsers[$user['id']] = $user;
                $currentUserIds[] = $user['id'];
            }

            $remainingUserIds = [];
            /** @var modUserGroupMember[] $existingMemberships */
            $existingMemberships = $this->object->getMany('UserGroupMembers');
            foreach ($existingMemberships as $existingMembership) {
                if (!in_array($existingMembership->get('member'), $currentUserIds)) {
                    $existingMembership->remove();
                } else {
                    $existingUser = $currentUsers[$existingMembership->get('member')];
                    $existingMembership->fromArray(['role' => $existingUser['role']]);
                    $remainingUserIds[] = $existingMembership->get('member');
                }
            }

            $newUserIds = array_diff($currentUserIds, $remainingUserIds);
            $newUsers = [];
            foreach ($users as $user) {
                if (in_array($user['id'], $newUserIds)) {
                    $newUsers[] = $user;
                }
            }

            foreach ($newUsers as $newUser) {
                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject(modUserGroupMember::class);
                $membership->fromArray([
                    'user_group' => $this->object->get('id'),
                    'role' => empty($newUser['role']) ? 0 : $newUser['role'],
                    'member' => $newUser['id']
                ]);

                $user = $this->modx->getObject(modUser::class, $newUser['id']);
                /* invoke OnUserBeforeAddToGroup event */
                $OnUserBeforeAddToGroup = $this->modx->invokeEvent('OnUserBeforeAddToGroup', [
                    'user' => &$user,
                    'usergroup' => &$this->object,
                    'membership' => &$membership,
                ]);
                $canSave = $this->processEventResponse($OnUserBeforeAddToGroup);
                if (!empty($canSave)) {
                    return $this->failure($canSave);
                }

                if ($membership->save()) {
                    $memberships[] = $membership;
                } else {
                    return $this->failure($this->modx->lexicon('user_group_member_err_save'));
                }

                /* invoke OnUserAddToGroup event */
                $this->modx->invokeEvent('OnUserAddToGroup', [
                    'user' => &$user,
                    'usergroup' => &$this->object,
                    'membership' => &$membership,
                ]);
            }
            $flush = true;
        }
        if ($flush) {
            $this->modx->cacheManager->flushPermissions();
        }

        return $memberships;
    }

    public function isAdminGroup()
    {
        return $this->object->get('id') === 1 || $this->object->get('name') === 'Administrator';
    }
}
