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
 * Get a menu item
 *
 * @param string $text The ID of the menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */

class modMenuGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modMenu';
    public $languageTopics = array('action','menu');
    public $permission = 'menus';
    public $objectType = 'menu';
    public $primaryKeyField = 'text';
}
return 'modMenuGetProcessor';
