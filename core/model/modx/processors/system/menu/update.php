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
if (empty($scriptProperties['text'])) return $modx->error->failure($modx->lexicon('menu_err_ns'));
$menu = $modx->getObject('modMenu',$scriptProperties['text']);
if ($menu == null) return $modx->error->failure($modx->lexicon('menu_err_nf'));

/* verify action */
if (!isset($scriptProperties['action_id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
if (!empty($scriptProperties['action_id'])) {
	$action = $modx->getObject('modAction',$scriptProperties['action_id']);
	if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));
}

/* verify parent */
if (!isset($scriptProperties['parent'])) return $modx->error->failure($modx->lexicon('menu_parent_err_ns'));
if (!empty($scriptProperties['parent'])) {
	$parent = $modx->getObject('modMenu',$scriptProperties['parent']);
	if ($parent == null) return $modx->error->failure($modx->lexicon('menu_parent_err_nf'));
}

/* save menu */
$menu->fromArray($scriptProperties);
$menu->set('action',$scriptProperties['action_id']);

if ($menu->save() == false) {
    return $modx->error->failure($modx->lexicon('menu_err_save'));
}


/* if changing key */
if (!empty($scriptProperties['new_text']) && $scriptProperties['new_text'] != $menu->get('text')) {
    $alreadyExists = $modx->getObject('modMenu',$scriptProperties['new_text']);
    if ($alreadyExists) { return $modx->error->failure($modx->lexicon('menu_err_ae')); }


    $children = $modx->getCollection('modMenu',array(
        'parent' => $menu->get('text'),
    ));

    $newMenu = $modx->newObject('modMenu');
    $newMenu->fromArray($menu->toArray());
    $newMenu->set('text',$scriptProperties['new_text']);
    if ($newMenu->save()) {
        foreach ($children as $child) {
            $child->set('parent',$newMenu->get('text'));
            $child->save();
        }
        $menu->remove();
    }
}

/* log manager action */
$modx->logManagerAction('menu_update','modMenu',$menu->get('text'));

return $modx->error->success('',$menu);