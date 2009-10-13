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

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modUserGroup');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$groups = $modx->getCollection('modUserGroup',$c);

$count = $modx->getCount('modUserGroup');

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