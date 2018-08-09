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
 * Generate a menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */

class modMenuGetMenuProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('menus');
    }

    public function getLanguageTopics() {
        return array('action','menu');
    }

    public function process() {
        $cacheManager = $this->modx->getCacheManager();
        $menus = $cacheManager->get('mgr/menus', array(
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_menu_key', null, 'menu'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_menu_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $this->modx->getOption('cache_menu_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));

        return $this->success('',$menus);
    }
}

return 'modMenuGetMenuProcessor';
