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
$id = $modx->getOption('id',$scriptProperties,'');

$id = str_replace('n_','',$id);
if (empty($id)) $id = '';

$c = $modx->newQuery('modMenu');
$c->leftJoin('modMenu','Children');
$c->leftJoin('modAction','Action');
$c->select('
    `modMenu`.*,
    `Action`.`controller` AS `controller`,
    COUNT(`Children`.`text`) AS `childrenCount`
');
$c->where(array(
    'modMenu.parent' => $id,
));
$c->sortby('modMenu.menuindex','ASC');
$c->groupby('modMenu.text');
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
	$text = $modx->lexicon($menu->get('text'));

    $contextMenu = array();
    $contextMenu[] = array(
        'text' => $modx->lexicon('menu_update'),
        'handler' => 'function(itm,e) { this.updateMenu(itm,e); }',
    );
    $contextMenu[] = '-';
    $contextMenu[] = array(
        'text' => $modx->lexicon('action_place_here'),
        'handler' => 'function(itm,e) { this.createMenu(itm,e); }',
    );
    $contextMenu[] = '-';
    $contextMenu[] = array(
        'text' => $modx->lexicon('menu_remove'),
        'handler' => 'function(itm,e) { this.removeMenu(itm,e); }',
    );

	$list[] = array(
		'text' => $text.($controller != '' ? ' <i>('.$controller.')</i>' : ''),
		'id' => 'n_'.$menu->get('text'),
        'cls' => 'icon-menu',
		'type' => 'menu',
        'pk' => $menu->get('text'),
		'leaf' => false,
        //'expandable' => $menu->get('childrenCount') <= 0 ? true : false,
        'data' => $menu->toArray(),
		'menu' => array('items' => $contextMenu),
	);
}

return $this->toJSON($list);