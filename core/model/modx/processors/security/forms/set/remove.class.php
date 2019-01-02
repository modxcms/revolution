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
 * Remove a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class modFormCustomizationSetRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modFormCustomizationSet';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'action';
}
return 'modFormCustomizationSetRemoveProcessor';
