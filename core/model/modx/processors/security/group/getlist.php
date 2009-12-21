<?php
/**
 * Gets a list of user groups
 *
 * @param boolean $combo (optional) If true, will append a (anonymous) row
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* build query */
$c = $modx->newQuery('modUserGroup');
$count = $modx->getCount('modUserGroup',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$groups = $modx->getCollection('modUserGroup',$c);

/* iterate */
$list = array();
if (!empty($_REQUEST['addNone'])) {
    $list[] = array(
        'id' => 0,
        'name' => $modx->lexicon('none'),
        'parent' => 0,
    );
}
if (!empty($_REQUEST['combo'])) {
    $list[] = array(
        'id' => '',
        'name' => ' ('.$modx->lexicon('anonymous').') ',
        'parent' => 0,
    );
}
foreach ($groups as $group) {
	$list[] = $group->toArray();
}
return $this->outputArray($list,$count);