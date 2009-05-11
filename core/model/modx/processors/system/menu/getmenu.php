<?php
/**
 * Generate a menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu');

$menus = $modx->cacheManager->get('mgr/menus');

return $modx->error->success('',$menus);