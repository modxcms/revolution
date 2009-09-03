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
$modx->lexicon->load('action','menu','topmenu');

if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['id'])) $_REQUEST['id'] = 'n_';

$id = str_replace('n_','',$_REQUEST['id']);
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
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$menus = $modx->getCollection('modMenu',$c);


$as = array();
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

	$as[] = array(
		'text' => $text.($controller != '' ? ' <i>('.$controller.')</i>' : ''),
		'id' => 'n_'.$menu->get('text'),
        'cls' => 'menu',
		'type' => 'menu',
        'pk' => $menu->get('text'),
		'leaf' => $menu->get('childrenCount') <= 0 ? true : false,
        'data' => $menu->toArray(),
		'menu' => array(
            'items' => array(
                array(
                    'text' => $modx->lexicon('menu_update'),
                    'handler' => 'function(itm,e) {
                        this.updateMenu(itm,e);
                    }',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('action_place_here'),
                    'handler' => 'function(itm,e) {
                        this.createMenu(itm,e);
                    }',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('menu_remove'),
                    'handler' => 'function(itm,e) {
                        this.removeMenu(itm,e);
                    }',
                ),
            ),
        )
	);
}

return $this->toJSON($as);