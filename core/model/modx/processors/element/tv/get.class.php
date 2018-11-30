<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__).'/get.class.php');
/**
 * Gets a TV
 *
 * @param integer $id The ID of the TV
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class modTemplateVarGetProcessor extends modElementGetProcessor {
    public $classKey = 'modTemplateVar';
    public $languageTopics = array('tv','category');
    public $permission = 'view_tv';
    public $objectType = 'tv';

    public function beforeOutput() {
        parent::beforeOutput();
        $this->object->set('els',$this->object->get('elements'));
    }
}
return 'modTemplateVarGetProcessor';
