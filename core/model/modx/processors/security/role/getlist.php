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
if ($sort == 'rolename_link') $sort = 'name';

/* build query */
$c = $modx->newQuery('modUserGroupRole');
$count = $modx->getCount('modUserGroupRole',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$roles = $modx->getCollection('modUserGroupRole', $c);

/* iterate */
$list = array();
if (!empty($scriptProperties['addNone'])) {
    $list[] = array('id' => 0, 'name' => $modx->lexicon('none'));
}
$hasRemove = $modx->hasPermission('delete_role');
foreach ($roles as $role) {
	$roleArray = $role->toArray();
    $menu = array();
    if ($hasRemove) {
        $menu[] = array(
            'text' => $modx->lexicon('role_remove'),
            'handler' => 'this.remove.createDelegate(this,["role_remove_confirm"])',
        );
    }
    $roleArray['menu'] = $menu;
	$list[] = $roleArray;
}
return $this->outputArray($list,$count);