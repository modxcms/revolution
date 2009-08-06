<?php
/**
 * Gets a list of users
 *
 * @param string $username (optional) Will filter the grid by searching for this
 * username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.user
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'username';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if ($_REQUEST['sort'] == 'username_link') $_REQUEST['sort'] = 'username';

$limit = true;
$c = $modx->newQuery('modUser');
$c->bindGraph('{"Profile":{}}');

if (isset($_REQUEST['username']) && $_REQUEST['username'] != '') {
    $c->where(array(
        'username LIKE "%'.$_REQUEST['username'].'%"',
    ));
    $limit = false;
}

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$users = $modx->getCollectionGraph('modUser', '{"Profile":{}}', $c);

$count = $modx->getCount('modUser');

$list = array();
foreach ($users as $user) {
	$profileArray = $user->Profile->toArray();
	$userArray = $user->toArray();
	$userArray = array_merge($profileArray,$userArray);
    $userArray['menu'] = array(
        array(
            'text' => $modx->lexicon('user_update'),
            'handler' => 'this.update',
        ),
        '-',
        array(
            'text' => $modx->lexicon('user_remove'),
            'handler' => 'this.remove',
        ),
    );
	$list[] = $userArray;
}
return $this->outputArray($list,$count);