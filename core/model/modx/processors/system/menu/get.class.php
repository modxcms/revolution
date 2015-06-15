<?php
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