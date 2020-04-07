<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Profile;

use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\modFormCustomizationProfileUserGroup;
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modUserGroup;

/**
 * Update a FC Profile
 * @package MODX\Revolution\Processors\Security\Forms\Profile
 */
class Update extends UpdateProcessor
{
    public $classKey = modFormCustomizationProfile::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'profile';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $active = $this->getProperty('active');
        if ($active !== null) {
            $this->object->set('active', (boolean)$active);
        }
        return parent::beforeSave();
    }

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function afterSave()
    {
        $this->setUserGroups();
        return parent::afterSave();
    }

    /**
     * @throws \xPDO\xPDOException
     */
    public function setUserGroups()
    {
        /* get usergroups */
        $userGroups = $this->getProperty('usergroups', null);
        if ($userGroups !== null) {
            /* erase old ProfileUserGroup records */
            $profileUserGroups = $this->modx->getCollection(modFormCustomizationProfileUserGroup::class, [
                'profile' => $this->object->get('id')
            ]);
            /** @var modFormCustomizationProfileUserGroup $profileUserGroup */
            foreach ($profileUserGroups as $profileUserGroup) {
                $profileUserGroup->remove();
            }

            /* reassign */
            $userGroups = is_array($userGroups) ? $userGroups : $this->modx->fromJSON($userGroups);
            foreach ($userGroups as $userGroupArray) {
                if (empty($userGroupArray)) {
                    continue;
                }
                /** @var modUserGroup $userGroup */
                $userGroup = $this->modx->getObject(modUserGroup::class, $userGroupArray['id']);
                if ($userGroup !== null) {
                    $profileUserGroup = $this->modx->newObject(modFormCustomizationProfileUserGroup::class);
                    $profileUserGroup->set('usergroup', $userGroup->get('id'));
                    $profileUserGroup->set('profile', $this->object->get('id'));
                    $profileUserGroup->save();
                }
            }
        }
    }
}
