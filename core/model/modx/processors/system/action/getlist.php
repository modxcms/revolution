<?php
/**
 * Grabs a list of actions
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to controller.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'controller';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modAction');
$c->sortby('namespace','ASC');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$actions = $modx->getCollection('modAction',$c);
$count = $modx->getCount('modAction');

$list = array();
$list[] = array('id' => 0, 'controller' => $modx->lexicon('action_none'));
foreach ($actions as $action) {
	$aa = $action->toArray();

	if (strlen($aa['controller']) > 1 && substr($aa['controller'],strlen($aa['controller'])-4,strlen($aa['controller'])) != '.php') {
		if (!file_exists($modx->getOption('manager_path').'controllers/'.$aa['controller'].'.php')) {
			$aa['controller'] .= '/index.php';
			$aa['controller'] = strtr($aa['controller'],'//','/');
		} else {
			$aa['controller'] .= '.php';
		}
	}

    $aa['controller'] = $aa['namespace'].' - '.$aa['controller'];

	$list[] = $aa;
}
return $this->outputArray($list,$count);