<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Menu;

use MODX\Revolution\modMenu;
use MODX\Revolution\modObjectRemoveProcessor;

/**
 * Remove a menu item
 * @param string $text The ID of the menu item
 * @package MODX\Revolution\Processors\System\Menu
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = modMenu::class;
    public $languageTopics = ['action', 'menu'];
    public $permission = 'menus';
    public $objectType = 'menu';
    public $primaryKeyField = 'text';

    public function cleanup()
    {
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh([
            'menu' => [],
        ]);
        parent::cleanup();
    }
}
