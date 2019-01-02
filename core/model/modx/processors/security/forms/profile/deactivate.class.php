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
 * Deactivate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileDeactivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $objectType = 'profile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';

    public function beforeSave() {
        $this->object->set('active', false);
        return parent::beforeSave();
    }
}

return 'modFormCustomizationProfileDeactivateProcessor';
