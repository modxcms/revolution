<?php

namespace MODX\Processors\System\Menu;

use MODX\Processors\modObjectGetProcessor;

/**
 * Get a menu item
 *
 * @param string $text The ID of the menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */
class Get extends modObjectGetProcessor
{
    public $classKey = 'modMenu';
    public $languageTopics = ['action', 'menu'];
    public $permission = 'menus';
    public $objectType = 'menu';
    public $primaryKeyField = 'text';
}