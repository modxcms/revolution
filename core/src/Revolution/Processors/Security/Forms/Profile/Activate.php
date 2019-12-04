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
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Activate a FC Profile
 * @package MODX\Revolution\Processors\Security\Forms\Profile
 */
class Activate extends UpdateProcessor
{
    public $classKey = modFormCustomizationProfile::class;
    public $objectType = 'profile';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->set('active', true);

        return parent::beforeSave();
    }
}
