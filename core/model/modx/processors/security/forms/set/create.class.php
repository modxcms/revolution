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
 * Create a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class modFormCustomizationSetCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modFormCustomizationSet';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'set';

    public function beforeSave() {
        $this->object->set('constraint_class','modResource');
        $actionId = $this->getProperty('action_id',null);
        if ($actionId !== null) {
            $this->object->set('action',$actionId);
        }
        return parent::beforeSave();
    }
}
return 'modFormCustomizationSetCreateProcessor';
