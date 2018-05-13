<?php

namespace MODX\Processors\System\Menu;

use MODX\Processors\modProcessor;
use xPDO\Cache\xPDOCacheManager;
use xPDO\xPDO;

/**
 * Generate a menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */
class GetMenu extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('menus');
    }


    public function getLanguageTopics()
    {
        return ['action', 'menu'];
    }


    public function process()
    {
        $cacheManager = $this->modx->getCacheManager();
        $menus = $cacheManager->get('mgr/menus', [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_menu_key', null, 'menu'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_menu_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$this->modx->getOption('cache_menu_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);

        return $this->success('', $menus);
    }
}
