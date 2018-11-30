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
 * Activate a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class modFormCustomizationSetActivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationSet';
    public $objectType = 'set';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';

    public function beforeSet() {
        $this->unsetProperty('action');
        return parent::beforeSet();
    }

    public function beforeSave() {
        $this->object->set('active', true);
        return parent::beforeSave();
    }
}

return 'modFormCustomizationSetActivateProcessor';
