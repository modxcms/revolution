<?php
/**
 * Generate a menu
 *
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu');

$menus = getSubMenus(0);

$as = array();
foreach ($menus as $menu) {
    $menu['children'] = getSubMenus($menu);
    $as[] = $menu;
}

return $modx->error->success('',$as);


function getSubMenus($menu) {
    global $modx;

    $c = $modx->newQuery('modMenu');
    $c->select('modMenu.*,Action.controller AS controller');
    $c->leftJoin('modAction','Action');
    $c->where(array(
        'modMenu.parent' => is_numeric($menu) ? 0 : $menu['id'],
    ));
    $c->sortby('`modMenu`.`menuindex`','ASC');
    $menus = $modx->getCollection('modMenu',$c);
    $av = array();
    foreach ($menus as $menu) {

        /* if 3rd party menu item, load proper text */
        $action = $menu->getOne('Action');
        $ma = $menu->toArray();
        if ($action) {
            $ns = $action->getOne('modNamespace');
            if ($ns != null && $ns->get('name') != 'core') {
                $modx->lexicon->load($ns->get('name').':default');
                $ma['text'] = $modx->lexicon($menu->get('text'));
            } else {
                $ma['text'] = $modx->lexicon($menu->get('text'));
            }
        } else {
            $ma['text'] = $modx->lexicon($menu->get('text'));
        }

        $desc = $menu->get('description');
        if ($desc != '' && $desc != null && $modx->lexicon->exists($desc)) {
            $ma['description'] = $modx->lexicon($desc);
        } else {
            $ma['description'] = '';
        }
        $ma['children'] = getSubMenus($menu->get('id'));

        if ($menu->get('controller')) {
            $ma['controller'] = $menu->get('controller');
        } else {
            $ma['controller'] = '';
        }
        $av[] = $ma;
    }
    return $av;
}