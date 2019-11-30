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

use MODX\Revolution\modActionDom;
use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\modFormCustomizationProfileUserGroup;
use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Model\DuplicateProcessor;

/**
 * Duplicate a FC Profile
 * @package MODX\Revolution\Processors\Security\Forms\Profile
 */
class Duplicate extends DuplicateProcessor
{
    public $classKey = modFormCustomizationProfile::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'profile';
    public $checkSavePermission = false;

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->newObject->set('active', false);

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->duplicateUserGroupAccess();
        $this->duplicateSets();

        return parent::afterSave();
    }

    /**
     * Duplicate the user group access on the old profile
     * @return void
     */
    public function duplicateUserGroupAccess()
    {
        $profileUserGroups = $this->modx->getCollection(modFormCustomizationProfileUserGroup::class, [
            'profile' => $this->object->get('id'),
        ]);
        /** @var modFormCustomizationProfileUserGroup $profileUserGroup */
        foreach ($profileUserGroups as $profileUserGroup) {
            /** @var modFormCustomizationProfileUserGroup $newProfileUserGroup */
            $newProfileUserGroup = $this->modx->newObject(modFormCustomizationProfileUserGroup::class);
            $newProfileUserGroup->set('usergroup', $profileUserGroup->get('usergroup'));
            $newProfileUserGroup->set('profile', $this->newObject->get('id'));
            $newProfileUserGroup->save();
        }
    }

    /**
     * Duplicate all the Sets of the old Profile
     * @return void
     */
    public function duplicateSets()
    {
        $sets = $this->object->getMany('Sets');
        /** @var modFormCustomizationSet $set */
        foreach ($sets as $set) {
            /** @var modFormCustomizationSet $newSet */
            $newSet = $this->modx->newObject(modFormCustomizationSet::class);
            $newSet->fromArray($set->toArray());
            $newSet->set('profile', $this->newObject->get('id'));
            $newSet->save();

            $rules = $set->getMany('Rules');
            /** @var modActionDom $rule */
            foreach ($rules as $rule) {
                /** @var modActionDom $newRule */
                $newRule = $this->modx->newObject(modActionDom::class);
                $newRule->fromArray($rule->toArray());
                $newRule->set('set', $newSet->get('id'));
                $newRule->save();
            }
        }
    }
}
