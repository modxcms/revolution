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
$c->leftJoin('modAction','Action');
$modMenuCols = $modx->getSelectColumns('modMenu','modMenu');
$c->select(array(
    $modMenuCols,
    'Action.controller',
    'Action.namespace',
));
$c->select('COUNT(Children.text) AS childrenCount');
$c->where(array(
    'modMenu.parent' => $id,
));
$c->sortby($sort,$dir);
$c->groupby($modMenuCols . ', controller, namespace');
$c->prepare();
if ($isLimit) $c->limit($limit,$start);
$menus = $modx->getCollection('modMenu',$c);

$list = array();
foreach ($menus as $menu) {
    $controller = $menu->get('controller');
    if (empty($controller)) $controller = '';
        if (strlen($controller) > 1 && substr($controller,strlen($controller)-4,strlen($controller)) != '.php') {
            if (!file_exists($modx->getOption('manager_path').'controllers/'.$controller.'.php')) {
                $controller .= '/index.php';
                $controller = strtr($controller,'//','/');
        } else {
            $controller .= '.php';
        }
    }
    $namespace = $menu->get('namespace');
    if(!in_array($namespace, array('core', '', null))) {
        $modx->lexicon->load($namespace . ':default');
    }
    $text = $modx->lexicon($menu->get('text'));

    $list[] = array(
        'text' => $text.($controller != '' ? ' <i>('.$controller.')</i>' : ''),
        'id' => 'n_'.$menu->get('text'),
        'cls' => 'icon-menu',
        'type' => 'menu',
        'pk' => $menu->get('text'),
        'leaf' => false,
        'data' => $menu->toArray(),
    );
}

return $this->toJSON($list);