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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Remove FC Profile
 * @package MODX\Revolution\Processors\Security\Forms\Profile
 */
class Remove extends RemoveProcessor
{
    public $classKey = modFormCustomizationProfile::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'action';
}
