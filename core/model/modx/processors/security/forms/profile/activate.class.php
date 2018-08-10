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
 * Activate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileActivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $objectType = 'profile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';

    public function beforeSave() {
        $this->object->set('active', true);
        return parent::beforeSave();
    }
}

return 'modFormCustomizationProfileActivateProcessor';
