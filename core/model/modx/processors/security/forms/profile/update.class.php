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
 * Update a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'profile';

    public function beforeSave() {
        $active = $this->getProperty('active',null);
        if ($active !== null) {
            $this->object->set('active',(boolean)$active);
        }
        return parent::beforeSave();
    }

    public function afterSave() {
        $this->setUserGroups();
        return parent::afterSave();
    }

    public function setUserGroups() {
        /* get usergroups */
        $userGroups = $this->getProperty('usergroups',null);
        if ($userGroups != null) {
            /* erase old ProfileUserGroup records */
            $profileUserGroups = $this->modx->getCollection('modFormCustomizationProfileUserGroup',array(
                'profile' => $this->object->get('id'),
            ));
            /** @var modFormCustomizationProfileUserGroup $profileUserGroup */
            foreach ($profileUserGroups as $profileUserGroup) { $profileUserGroup->remove(); }

            /* reassign */
            $userGroups = is_array($userGroups) ? $userGroups : $this->modx->fromJSON($userGroups);
            foreach ($userGroups as $userGroupArray) {
                if (empty($userGroupArray)) continue;
                /** @var modUserGroup $userGroup */
                $userGroup = $this->modx->getObject('modUserGroup',$userGroupArray['id']);
                if (!empty($userGroup)) {
                    $profileUserGroup = $this->modx->newObject('modFormCustomizationProfileUserGroup');
                    $profileUserGroup->set('usergroup',$userGroup->get('id'));
                    $profileUserGroup->set('profile',$this->object->get('id'));
                    $profileUserGroup->save();
                }
            }
        }
    }
}
return 'modFormCustomizationProfileUpdateProcessor';
