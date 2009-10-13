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
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'controller');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* get actions */
$c = $modx->newQuery('modAction');
$c->sortby('namespace','ASC');
$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}
$actions = $modx->getCollection('modAction',$c);
$count = $modx->getCount('modAction');

$list = array();
$list[] = array('id' => 0, 'controller' => $modx->lexicon('action_none'));
foreach ($actions as $action) {
	$actionArray = $action->toArray();

    $controller = $actionArray['controller'];
    $controllerLength = strlen($controller);

	if ($controllerLength > 1 && substr($controller,($controllerLength-4),$controllerLength) != '.php') {
		if (!file_exists($modx->getOption('manager_path').'controllers/'.$aa['controller'].'.php')) {
			$actionArray['controller'] .= '/index.php';
			$actionArray['controller'] = strtr($actionArray['controller'],'//','/');
		} else {
			$actionArray['controller'] .= '.php';
		}
	}

    $actionArray['controller'] = $actionArray['namespace'].' - '.$actionArray['controller'];

	$list[] = $actionArray;
}
return $this->outputArray($list,$count);