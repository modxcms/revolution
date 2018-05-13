<?php

namespace MODX\Processors\System\Menu;

use MODX\Processors\modObjectRemoveProcessor;


/**
 * Remove a menu item
 *
 * @param string $text The ID of the menu item
 *
 * @package modx
 * @subpackage processors.system.menu
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modMenu';
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