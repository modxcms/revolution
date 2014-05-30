<?php
/**
 * Get the menu items, in node format
 *
 * @param string $id The parent ID
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 *
 * @package modx
 * @subpackage processors.system.menu
 */
if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu','topmenu');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'menuindex');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$id = $modx->getOption('id',$scriptProperties,'');

$id = str_replace('n_','',$id);
if (empty($id)) $id = '';

$c = $modx->newQuery('modMenu');
$c->leftJoin('modMenu','Children');
$modMenuCols = $modx->getSelectColumns('modMenu','modMenu');
$c->select($modMenuCols);
$c->select(array(
    'COUNT(Children.text) AS childrenCount'
));
$c->where(array(
    'modMenu.parent' => $id,
));
$c->sortby($sort,$dir);
$c->groupby($modMenuCols . ', action, namespace');
$c->prepare();
if ($isLimit) $c->limit($limit,$start);
$menus = $modx->getCollection('modMenu',$c);

$list = array();
/** @var modMenu $menu */
foreach ($menus as $menu) {
    $controller = $menu->get('action');
    $namespace = $menu->get('namespace');
    if (!in_array($namespace, array('core', '', null))) {
        $modx->lexicon->load($namespace . ':default');
    }
    $text = $modx->lexicon($menu->get('text'));

    $list[] = array(
        'text' => $text.($controller != '' ? ' <i>('.$namespace.':'.$controller.')</i>' : ''),
        'id' => 'n_'.$menu->get('text'),
        'cls' => 'icon-menu',
        'iconCls' => 'icon icon-' . ( $menu->get('childrenCount') > 0 ? ( $menu->get('parent') === '' ? 'navicon' : 'folder' ) : 'terminal' ),
        'type' => 'menu',
        'pk' => $menu->get('text'),
        'leaf' => $menu->get('childrenCount') > 0 ? false : true,
        'data' => $menu->toArray(),
    );
}

return $this->toJSON($list);
