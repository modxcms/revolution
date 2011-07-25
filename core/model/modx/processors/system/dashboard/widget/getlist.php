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
 * @subpackage processors.system.dashboard.widget
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
$c = $modx->newQuery('modDashboardWidget');
if (!empty($scriptProperties['query'])) {
    $c->where(array('modDashboardWidget.name:LIKE' => '%'.$scriptProperties['query'].'%'));
    $c->orCondition(array('modDashboardWidget.description:LIKE' => '%'.$scriptProperties['query'].'%'));
}
$count = $modx->getCount('modDashboardWidget',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$widgets = $modx->getCollection('modDashboardWidget',$c);

/* iterate through users */
$list = array();
/** @var modDashboardWidget $widget */
foreach ($widgets as $widget) {
    $widgetArray = $widget->toArray();
    if ($widget->get('lexicon') != 'core:dashboards') {
        $modx->lexicon->load($widget->get('lexicon'));
    }
    $widgetArray['description_trans'] = $modx->lexicon($widget->get('description'));
    $widgetArray['cls'] = 'pupdate premove';
    $list[] = $widgetArray;
}
return $this->outputArray($list,$count);