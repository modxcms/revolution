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
 * Create a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'profile';
}
return 'modFormCustomizationProfileCreateProcessor';
