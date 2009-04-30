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

if (!isset($_POST['action_id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
if ($_POST['action_id'] == 0) {
	$action = $modx->newObject('modAction');
	$action->set('id',0);
} else {
	$action = $modx->getObject('modAction',$_POST['action_id']);
	if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));
}
if (!isset($_POST['parent'])) return $modx->error->failure($modx->lexicon('menu_parent_err_ns'));
if ($_POST['parent'] == 0) {
	$parent = $modx->newObject('modMenu');
	$parent->set('id',0);
} else {
	$parent = $modx->getObject('modMenu',$_POST['parent']);
	if ($parent == null) return $modx->error->failure($modx->lexicon('menu_parent_err_nf'));
}

$count = $modx->getCount('modMenu',array('parent' => $parent->get('id')));

$menu = $modx->newObject('modMenu');
$menu->set('parent',$parent->get('id'));
$menu->set('action',$action->get('id'));
$menu->set('text',isset($_POST['text']) ? $_POST['text'] : '');
$menu->set('icon',isset($_POST['icon']) ? $_POST['icon'] : '');
$menu->set('menuindex',$count);
$menu->set('params',isset($_POST['params']) ? $_POST['params'] : '');
$menu->set('handler',isset($_POST['handler']) ? $_POST['handler'] : '');

if (!$menu->save()) return $modx->error->failure($modx->lexicon('menu_err_save'));

/* log manager action */
$modx->logManagerAction('menu_create','modMenu',$menu->get('id'));

return $modx->error->success();