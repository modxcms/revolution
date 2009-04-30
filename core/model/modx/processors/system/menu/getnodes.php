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
$modx->lexicon->load('action','menu');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['id'])) $_REQUEST['id'] = 'n_0';

$id = str_replace('n_','',$_REQUEST['id']);

$c = $modx->newQuery('modMenu');
$c->where(array('parent' => $id));
$c->sortby('menuindex','ASC');
/* $c->limit($_REQUEST['limit'],$_REQUEST['start']); */

$menus = $modx->getCollection('modMenu',$c);

$cc = $modx->newQuery('modMenu');
$cc->where(array('parent' => $id));
$count = $modx->getCount('modMenu',$cc);

$as = array();
foreach ($menus as $menu) {
	$action = $menu->getOne('Action');
	$controller = $action != NULL && $action->get('controller') != '' ? $action->get('controller') : '';
	if (strlen($controller) > 1 && substr($controller,strlen($controller)-4,strlen($controller)) != '.php') {
		if (!file_exists($modx->config['manager_path'].'controllers/'.$controller.'.php')) {
			$controller .= '/index.php';
			$controller = strtr($controller,'//','/');
		} else {
			$controller .= '.php';
		}
	}
	$text = $modx->lexicon($menu->get('text'));

	$as[] = array(
		'text' => $text.($controller != '' ? ' <i>('.$controller.')</i>' : ''),
		'id' => 'n_'.$menu->get('id'),
		'leaf' => 0,
		'cls' => 'folder',
		'type' => 'menu',
		'menu' => array(
            'items' => array(
                array(
                    'text' => $modx->lexicon('menu_update'),
                    'handler' => 'function(itm,e) {
                        this.update(itm,e);
                    }',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('action_place_here'),
                    'handler' => 'function(itm,e) {
                        this.create(itm,e);
                    }',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('menu_remove'),
                    'handler' => 'function(itm,e) {
                        this.remove(itm,e);
                    }',
                ),
            ),
        )
	);
}

return $this->toJSON($as);