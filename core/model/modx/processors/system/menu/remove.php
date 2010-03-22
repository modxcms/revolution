<?php
/**
 * Remove a menu item
 *
 * @param integer $id The ID of the menu item
 *
 * @package modx
 * @subpackage processors.system.menu
 */
if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

/* get menu */
if (empty($scriptProperties['text'])) return $modx->error->failure($modx->lexicon('menu_err_ns'));
$menu = $modx->getObject('modMenu',array('text' => $scriptProperties['text']));
if ($menu == null) return $modx->error->failure($modx->lexicon('menu_err_nf'));

/* remove menu */
if ($menu->remove() == false) {
    return $modx->error->failure($modx->lexicon('menu_err_remove'));
}

/* log manager action */
$modx->logManagerAction('menu_delete','modMenu',$menu->get('text'));

return $modx->error->success('',$menu);