<?php

/**
 * Remove a menu item
 *
 * @param string $text The ID of the menu item
 *
 * @package modx
 * @subpackage processors.system.menu
 */

class modMenuRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modMenu';
    public $languageTopics = array('action','menu');
    public $permission = 'menus';
    public $objectType = 'menu';
    public $primaryKeyField = 'text';


    public function cleanup() {
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh(array(
            'menu' => array(),
        ));
        return parent::cleanup();
    }
}
return 'modMenuRemoveProcessor';