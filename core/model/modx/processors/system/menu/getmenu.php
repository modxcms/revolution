<?php
/**
 * Generate a menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu');

$menus = $modx->cacheManager->get('mgr/menus', array(
    xPDO::OPT_CACHE_KEY => $modx->getOption('cache_menu_key', null, 'menu'),
    xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_menu_handler', null, $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
    xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_menu_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
));

return $modx->error->success('',$menus);