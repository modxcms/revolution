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

use MODX\Revolution\Processors\Model\DuplicateProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\modUserSetting;

/**
 * Duplicates a user.
 * @param integer $id The user to duplicate
 * @param string $new_username The name of the new user.
 * @package MODX\Revolution\Processors\Security\User
 */
class Duplicate extends DuplicateProcessor
{
    public $classKey = modUser::class;
    public $languageTopics = ['user'];
    public $permission = 'new_user';
    public $objectType = 'user';
    public $nameField = 'username';
    public $beforeSaveEvent = 'OnBeforeUserDuplicate';
    public $afterSaveEvent = 'OnUserDuplicate';

    /**
     * Get the new name for the duplicate
     *
     * @return string
     */
    public function getNewName()
    {
        $name = $this->getProperty('new_username', '');
        $newName = !empty($name) ? $name : $this->modx->lexicon(
            'duplicate_of',
            ['name' => $this->object->get('username')]
        );

        return $newName;
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        /* copy profile */
        $profile = $this->object->getOne('Profile');
        if ($profile) {
            // Reset some modUserProfile fields
            $profileData = array_merge($profile->toArray(), [
                'logincount' => '',
                'lastlogin' => '',
                'thislogin' => '',
                'failedlogincount' => '',
                'sessionid' => '',
            ]);
            unset($profileData['internalKey']);

            /** @var modUserProfile $newProfile */
            $newProfile = $this->modx->newObject(modUserProfile::class);
            $newProfile->fromArray($profileData);
            $this->newObject->addOne($newProfile);
        }

        /* copy user group memberships */
        $memberships = $this->object->getMany('UserGroupMembers');
        $newMemberships = [];
        /** @var modUserGroupMember $membership */
        foreach ($memberships as $membership) {
            /** @var modUserGroupMember $newMembership */
            $newMembership = $this->modx->newObject(modUserGroupMember::class);
            $newMembership->fromArray($membership->toArray());
            $newMemberships[] = $newMembership;
        }
        $this->newObject->addMany($newMemberships);

        /* copy settings */
        $settings = $this->object->getMany('UserSettings');
        $newSettings = [];
        /** @var modUserSetting $setting */
        foreach ($settings as $setting) {
            /** @var modUserSetting $newSetting */
            $newSetting = $this->modx->newObject(modUserSetting::class);
            $newSetting->fromArray($setting->toArray());
            $newSetting->set('key', $setting->get('key'));
            $newSettings[] = $newSetting;
        }
        $this->newObject->addMany($newSettings);

        // Unset some modUser fields
        $this->object->set('session_stale');

        return parent::beforeSave();
    }

    public function cleanup()
    {
        return $this->success('', $this->newObject->get(['id', 'username']));
    }
}
