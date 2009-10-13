<?php
/**
 * Update a menu item
 *
 * @param integer $id The ID of the menu item
 * @param string $text The text of the menu button.
 * @param string $icon
 * @param string $params (optional) Any parameters to be sent over GET when
 * clicking the menu
 * @param string $handler (optional) A custom javascript handler for the menu
 * item
 * @param integer $action_id (optional) The ID of the action. Defaults to 0.
 * @param integer $parent (optional) The parent menu to create from. Defaults to
 * 0.
 *
 * @package modx
 * @subpackage processors.system.menu
 */
if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

/* get menu */
if (empty($_POST['text'])) return $modx->error->failure($modx->lexicon('menu_err_ns'));
$menu = $modx->getObject('modMenu',$_POST['text']);
if ($menu == null) return $modx->error->failure($modx->lexicon('menu_err_nf'));

/* verify action */
if (!isset($_POST['action_id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
if (!empty($_POST['action_id'])) {
	$action = $modx->getObject('modAction',$_POST['action_id']);
	if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));
}

/* verify parent */
if (!isset($_POST['parent'])) return $modx->error->failure($modx->lexicon('menu_parent_err_ns'));
if (!empty($_POST['parent'])) {
	$parent = $modx->getObject('modMenu',$_POST['parent']);
	if ($parent == null) return $modx->error->failure($modx->lexicon('menu_parent_err_nf'));
}

/* save menu */
$menu->fromArray($_POST);
$menu->set('action',$_POST['action_id']);
if ($menu->save() == false) {
    return $modx->error->failure($modx->lexicon('menu_err_save'));
}

/* log manager action */
$modx->logManagerAction('menu_update','modMenu',$menu->get('text'));

return $modx->error->success('',$menu);