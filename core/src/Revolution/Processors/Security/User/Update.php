<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Security\User;


use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\modX;

/**
 * Update a user.
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class Update extends UpdateProcessor {
    public $classKey = modUser::class;
    public $languageTopics = ['user'];
    public $permission = 'save_user';
    public $objectType = 'user';
    public $beforeSaveEvent = 'OnBeforeUserFormSave';
    public $afterSaveEvent = 'OnUserFormSave';

    /** @var boolean $activeStatusChanged */
    public $activeStatusChanged = false;
    /** @var boolean $newActiveStatus */
    public $newActiveStatus = false;

    /** @var modUser $object */
    public $object;
    /** @var modUserProfile $profile */
    public $profile;
    /** @var Validation $validator */
    public $validator;
    /** @var string $newPassword */
    public $newPassword = '';


    /**
     * Allow for Users to use derivative classes for their processors
     *
     * @static
     * @param modX $modx
     * @param string $className
     * @param array $properties
     * @return Processor
     */
    public static function getInstance(modX $modx,$className,$properties = []) {
        $classKey = !empty($properties['class_key']) ? $properties['class_key'] : modUser::class;
        $object = $modx->newObject($classKey);

        if (!in_array($classKey, ['modUser',''])) {
            $className = $classKey.'UpdateProcessor';
            if (!class_exists($className)) {
                $className = static::class;
            }
        }
        /** @var Processor $processor */
        $processor = new $className($modx,$properties);
        return $processor;
    }

    /**
     * {@inheritDoc}
     * @return boolean|string
     */
    public function initialize() {
        $this->setDefaultProperties(
            [
            'class_key' => $this->classKey,
            ]
        );
        return parent::initialize();
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSet() {
        $this->setCheckbox('blocked');
        $this->setCheckbox('active');
        $this->setCheckbox('sudo');
        return parent::beforeSet();
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSave() {
        $this->setProfile();
        $this->setRemoteData();

        if ($this->modx->hasPermission('set_sudo')) {
            $sudo = $this->getProperty('sudo', null);
            if ($sudo !== null) {
                $this->object->setSudo(!empty($sudo));
            }
        }

        $this->validator = new Validation($this,$this->object,$this->profile);
        $this->validator->validate();
        $canChangeStatus = $this->checkActiveChange();
        if ($canChangeStatus !== true) {
            $this->addFieldError('active',$canChangeStatus);
        }
        return parent::beforeSave();
    }

    /**
     * Check for an active/inactive status change
     * @return boolean|string
     */
    public function checkActiveChange() {
        $this->activeStatusChanged = $this->object->isDirty('active');
        $this->newActiveStatus = $this->object->get('active');
        if ($this->activeStatusChanged) {
            $event = $this->newActiveStatus == true ? 'OnBeforeUserActivate' : 'OnBeforeUserDeactivate';
            $OnBeforeUserActivate = $this->modx->invokeEvent($event,
                [
                    'id' => $this->object->get('id'),
                    'user' => &$this->object,
                    'mode' => modSystemEvent::MODE_UPD,
                ]
            );
            $canChange = $this->processEventResponse($OnBeforeUserActivate);
            if (!empty($canChange)) {
                return $canChange;
            }
        }
        return true;
    }

    /**
     * Set the profile data for the user
     * @return modUserProfile
     */
    public function setProfile() {
        $this->profile = $this->object->getOne('Profile');
        if (empty($this->profile)) {
            $this->profile = $this->modx->newObject(modUserProfile::class);
            $this->profile->set('internalKey',$this->object->get('id'));
            $this->profile->save();
            $this->object->addOne($this->profile,'Profile');
        }
        $this->profile->fromArray($this->getProperties());
        return $this->profile;
    }

    /**
     * Set the remote data for the user
     * @return mixed
     */
    public function setRemoteData() {
        $remoteData = $this->getProperty('remoteData',null);
        if ($remoteData !== null) {
            $remoteData = is_array($remoteData) ? $remoteData : $this->modx->fromJSON($remoteData);
            $this->object->set('remote_data',$remoteData);
        }
        return $remoteData;
    }

    /**
     * Set user groups for the user
     * @return modUserGroupMember[] new added member groups
     */
    public function setUserGroups() {
        $memberships = [];
        $groups = $this->getProperty('groups', null);
        if ($groups !== null) {
            $groups = is_array($groups) ? $groups : json_decode($groups, true);
            $primaryGroupId = $this->object->get('primary_group');

            $currentGroups = [];
            $currentGroupIds = [];
            foreach ($groups as $group) {
                $currentGroups[$group['usergroup']] = $group;
                $currentGroupIds[] = $group['usergroup'];
            }

            if (!in_array($primaryGroupId, $currentGroupIds)) {
                $primaryGroupId = 0;
            }

            $remainingGroupIds = [];
            /** @var modUserGroupMember[] $existingMemberships */
            $existingMemberships = $this->object->getMany('UserGroupMembers');
            foreach ($existingMemberships as $existingMembership) {
                if (!in_array($existingMembership->get('user_group'), $currentGroupIds)) {
                    $existingMembership->remove();
                } else {
                    $existingGroup = $currentGroups[$existingMembership->get('user_group')];
                    $existingMembership->fromArray(
                        [
                            'role' => $existingGroup['role'],
                            'rank' => isset($existingGroup['rank']) ? $existingGroup['rank'] : 0
                        ]
                    );
                    $remainingGroupIds[] = $existingMembership->get('user_group');
                }
            }

            $newGroupIds = array_diff($currentGroupIds, $remainingGroupIds);
            $newGroups = [];
            foreach ($groups as $group) {
                if (in_array($group['usergroup'], $newGroupIds)) {
                    $newGroups[] = $group;
                }
                if (empty($group['rank'])) {
                    $primaryGroupId = $group['usergroup'];
                }
            }

            $idx = 0;
            foreach ($newGroups as $newGroup) {
                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject(modUserGroupMember::class);
                $membership->fromArray(
                    [
                        'user_group' => $newGroup['usergroup'],
                        'role' => $newGroup['role'],
                        'member' => $this->object->get('id'),
                        'rank' => isset($newGroup['rank']) ? $newGroup['rank'] : $idx
                    ]
                );
                if (empty($newGroup['rank'])) {
                    $primaryGroupId = $newGroup['usergroup'];
                }

                $usergroup = $this->modx->getObject(modUserGroup::class, $newGroup['usergroup']);
                /* invoke OnUserBeforeAddToGroup event */
                $OnUserBeforeAddToGroup = $this->modx->invokeEvent('OnUserBeforeAddToGroup', [
                    'user' => &$this->object,
                    'usergroup' => &$usergroup,
                    'membership' => &$membership,
                ]
                );
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
                    'user' => &$this->object,
                    'usergroup' => &$usergroup,
                    'membership' => &$membership,
                ]
                );

                $idx++;
            }
            $this->object->addMany($memberships, 'UserGroupMembers');
            $this->object->set('primary_group', $primaryGroupId);
            $this->object->save();
        }
        return $memberships;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function afterSave() {
        $this->setUserGroups();
        if ($this->activeStatusChanged) {
            $this->fireAfterActiveStatusChange();
        }
        return parent::afterSave();
    }

    /**
     * Fire the active status change event
     */
    public function fireAfterActiveStatusChange() {
        $event = $this->newActiveStatus == true ? 'OnUserActivate' : 'OnUserDeactivate';
        $this->modx->invokeEvent($event,
            [
                'id' => $this->object->get('id'),
                'user' => &$this->object,
                'mode' => modSystemEvent::MODE_UPD,
            ]
        );
    }

    /**
     * {@inheritDoc}
     * @return array|string
     */
    public function cleanup()
    {
        $userArray = $this->object->toArray();
        $profile = $this->object->getOne('Profile');
        if ($profile) {
            $userArray = array_merge($profile->toArray(),$userArray);
        }
        unset($userArray['password'], $userArray['cachepwd'], $userArray['sessionid'], $userArray['salt']);

        $passwordNotifyMethod = $this->getProperty('passwordnotifymethod');
        if (!empty($passwordNotifyMethod) && !empty($this->newPassword) && $passwordNotifyMethod  == 's') {
            return $this->success($this->modx->lexicon('user_updated_password_message',
                [
                    'username' => $this->object->get('username'),
                    'password' => $this->newPassword,
                ]
            ), $this->object);
        } else {
            return $this->success('',$this->object);
        }
    }
}
