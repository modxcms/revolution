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
 * Update a user group
 *
 * @param integer $id The ID of the user group
 * @param string $name The new name of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modUserGroup';
    public $languageTopics = array('user');
    public $permission = 'usergroup_save';
    public $objectType = 'user_group';
    public $beforeSaveEvent = 'OnUserGroupBeforeFormSave';
    public $afterSaveEvent = 'OnUserGroupFormSave';

    /**
     * Override the modObjectUpdateProcessor::initialize method to allow for grabbing the (anonymous) user group
     * @return boolean|string
     */
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) {
            $this->object = $this->modx->newObject('modUserGroup');
            $this->object->set('id',0);
        } else {
            $this->object = $this->modx->getObject('modUserGroup',$id);
            if (empty($this->object)) {
                return $this->modx->lexicon('user_group_err_not_found');
            }
        }
        return true;
    }

    /**
     * Override the saveObject method to prevent saving of the (anonymous) group
     *
     * {@inheritDoc}
     * @return boolean
     */
    public function saveObject() {
        $saved = true;
        if (!in_array($this->getProperty('id'),array('0',0,null))) {
            $saved = $this->object->save();
        }
        return $saved;
    }

    public function beforeSave() {
        $c = $this->modx->newQuery('modUserGroup');
        $c->where(array(
            'id:!=' => $this->object->get('id'),
            'name' => $this->getProperty('name')
        ));

        $count = $this->modx->getCount('modUserGroup', $c);
        if ($count > 0) {
            return $this->modx->lexicon('user_group_err_already_exists');
        }

        return parent::beforeSave();
    }

    public function afterSave() {
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->addUsers();
        }
        return parent::afterSave();
    }

    /**
     * Add users to the User Group
     *
     * @return modUserGroupMember[]
     */
    public function addUsers() {
        $users = $this->getProperty('users', null);
        $id = $this->getProperty('id');
        $memberships = array();
        $flush = false;

        if ($users !== null && !empty($id)) {
            $users = is_array($users) ? $users : json_decode($users, true);

            $currentUsers = array();
            $currentUserIds = array();
            foreach ($users as $user) {
                $currentUsers[$user['id']] = $user;
                $currentUserIds[] = $user['id'];
            }

            $remainingUserIds = array();
            /** @var modUserGroupMember[] $existingMemberships */
            $existingMemberships = $this->object->getMany('UserGroupMembers');
            foreach ($existingMemberships as $existingMembership) {
                if (!in_array($existingMembership->get('member'), $currentUserIds)) {
                    $existingMembership->remove();
                } else {
                    $existingUser = $currentUsers[$existingMembership->get('member')];
                    $existingMembership->fromArray(array(
                        'role' => $existingUser['role']
                    ));
                    $remainingUserIds[] = $existingMembership->get('member');
                }
            }

            $newUserIds = array_diff($currentUserIds, $remainingUserIds);
            $newUsers = array();
            foreach ($users as $user) {
                if (in_array($user['id'], $newUserIds)) {
                    $newUsers[] = $user;
                }
            }

            $idx = 0;
            foreach ($newUsers as $newUser) {
                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->fromArray(array(
                    'user_group' => $this->object->get('id'),
                    'role' => empty($newUser['role']) ? 0 : $newUser['role'],
                    'member' => $newUser['id']
                ));

                $user = $this->modx->getObject('modUser', $newUser['id']);
                /* invoke OnUserBeforeAddToGroup event */
                $OnUserBeforeAddToGroup = $this->modx->invokeEvent('OnUserBeforeAddToGroup', array(
                    'user' => &$user,
                    'usergroup' => &$this->object,
                    'membership' => &$membership,
                ));
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
                $this->modx->invokeEvent('OnUserAddToGroup', array(
                    'user' => &$user,
                    'usergroup' => &$this->object,
                    'membership' => &$membership,
                ));

                $idx++;
            }
            $flush = true;
        }
        if ($flush) {
            $this->modx->cacheManager->flushPermissions();
        }
        return $memberships;
    }
}
return 'modUserGroupUpdateProcessor';
