<?php
/**
 * Gets a list of roles
 *
 * @param boolean $addNone If true, will add a role of None
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.role
 */
if (!$modx->hasPermission('view_role')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'authority');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* build query */
$c = $modx->newQuery('modUserGroupRole');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$roles = $modx->getCollection('modUserGroupRole', $c);

$list = array();

/* if adding none as an option */
if (!empty($scriptProperties['addNone'])) {
    $list[] = array('id' => 0, 'name' => $modx->lexicon('none'));
}

/* iterate */
foreach ($roles as $role) {
	$roleArray = $role->toArray();
    $roleArray['name'] = $role->get('name').' - '.$role->get('authority');
    $roleArray['id'] = $role->get('authority');
	$list[] = $roleArray;
}
return $this->outputArray($list);