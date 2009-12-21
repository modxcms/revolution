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
if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'username');
if ($sort == 'username_link') $sort = 'username';
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* query for users */
$c = $modx->newQuery('modUser');
if (!empty($_REQUEST['username'])) {
    $c->where(array('username:LIKE' => '%'.$_REQUEST.'%'));
}
$count = $modx->getCount('modUser',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$c->bindGraph('{"Profile":{}}');
$users = $modx->getCollectionGraph('modUser', '{"Profile":{}}', $c);

/* iterate through users */
$list = array();
foreach ($users as $user) {
	$userArray = $user->toArray();
    if ($user->Profile instanceof modUserProfile) {
       $profileArray = $user->Profile->toArray();
	   $userArray = array_merge($profileArray,$userArray);
    }
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