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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Remove a menu item
 * @param string $text The ID of the menu item
 * @package MODX\Revolution\Processors\System\Menu
 */
class Remove extends RemoveProcessor
{
    public $classKey = modMenu::class;
    public $languageTopics = ['action', 'menu'];
    public $permission = 'menus';
    public $objectType = 'menu';
    public $primaryKeyField = 'text';

    /**
     * @return bool
     */
    public function beforeRemove()
    {
        $this->removeNested($this->object);

        return parent::beforeRemove();
    }

    /**
     * @param modMenu $menu
     */
    public function removeNested(modMenu $menu)
    {
        $criteria = ['parent' => $menu->get('text')];

        if (!$this->modx->getCount($this->classKey, $criteria)) {
            return;
        }

        foreach ($this->modx->getIterator($this->classKey, $criteria) as $subMenu) {
            $this->removeNested($subMenu);
            $this->modx->runProcessor('system/menu/remove', ['text' => $subMenu->get('text')]);
        }
    }

    public function cleanup()
    {
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh(['menu' => []]);
        parent::cleanup();
    }
}
