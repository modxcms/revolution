<?php
/**
 * Get a menu item
 *
 * @param integer $id The ID of the menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['text'])) return $modx->error->failure($modx->lexicon('menu_err_ns'));
$menu = $modx->getObject('modMenu',$_REQUEST['text']);
if ($menu == null) return $modx->error->failure($modx->lexicon('menu_err_nf'));

return $modx->error->success('',$menu);