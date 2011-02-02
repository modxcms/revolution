<?php
/**
 * Generate a menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu');

$menus = $modx->cacheManager->get('mgr/menus', array(
    xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_menu_key', null, 'menu'),
    xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_menu_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER))
));

return $modx->error->success('',$menus);