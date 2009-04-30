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
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'edit_role' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if ($_REQUEST['sort'] == 'rolename_link') $_REQUEST['sort'] = 'name';

$c = $modx->newQuery('modUserGroupRole');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$roles = $modx->getCollection('modUserGroupRole', $c);

$rs = array();
if (isset($_REQUEST['addNone']) && $_REQUEST['addNone']) {
    $rs[] = array('id' => 0, 'name' => $modx->lexicon('none'));
}

foreach ($roles as $r) {
	$ra = $r->toArray();
    $ra['menu'] = array(
        array(
            'text' => $modx->lexicon('role_remove'),
            'handler' => 'this.remove.createDelegate(this,["role_remove_confirm"])',
        )
    );
	$rs[] = $ra;
}
return $this->outputArray($rs);