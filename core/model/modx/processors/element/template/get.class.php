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
 * Gets a template
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
class modTemplateGetProcessor extends modElementGetProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template','category');
    public $permission = 'view_template';
    public $objectType = 'template';
}
return 'modTemplateGetProcessor';
