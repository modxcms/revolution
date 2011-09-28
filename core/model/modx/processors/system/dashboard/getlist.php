<?php
/**
 * Gets a list of dashboards
 *
 * @param string $username (optional) Will filter the grid by searching for this
 * username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 * 
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('dashboards')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('dashboards');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* query for users */
$c = $modx->newQuery('modDashboard');
if (!empty($scriptProperties['query'])) {
    $c->where(array('modDashboard.name:LIKE' => '%'.$scriptProperties['query'].'%'));
    $c->orCondition(array('modDashboard.description:LIKE' => '%'.$scriptProperties['query'].'%'));
}
if (!empty($scriptProperties['usergroup'])) {
    $c->innerJoin('modUserGroup','UserGroups');
    $c->where(array(
        'UserGroups.id' => $scriptProperties['usergroup'],
    ));
}
$count = $modx->getCount('modDashboard',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$dashboards = $modx->getCollection('modDashboard',$c);

/* iterate through users */
$list = array();
/** @var modDashboard $dashboard */
foreach ($dashboards as $dashboard) {
    $dashboardArray = $dashboard->toArray();
    $dashboardArray['cls'] = 'pupdate premove pduplicate';
    $list[] = $dashboardArray;
}
return $this->outputArray($list,$count);