<?php
/**
 * Creates a menu item
 *
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
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));

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

/* get new menuindex */
$count = $modx->getCount('modMenu',array('parent' => $parent->get('id')));

/* create menu */
$menu = $modx->newObject('modMenu');
$menu->fromArray($_POST);
$menu->set('menuindex',$count);

/* save menu */
if ($menu->save() == false) {
    return $modx->error->failure($modx->lexicon('menu_err_save'));
}

/* log manager action */
$modx->logManagerAction('menu_create','modMenu',$menu->get('id'));

return $modx->error->success();