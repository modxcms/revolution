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
if (!$modx->hasPermission(array('access_permissions' => true, 'view_user' => true))) {
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
$c->leftJoin('modUserProfile','Profile');
if (!empty($_REQUEST['query'])) {
    $c->where(array('modUser.username:LIKE' => '%'.$_REQUEST['query'].'%'));
    $c->orCondition(array('Profile.fullname:LIKE' => '%'.$_REQUEST['query'].'%'));
    $c->orCondition(array('Profile.email:LIKE' => '%'.$_REQUEST['query'].'%'));
}
$count = $modx->getCount('modUser',$c);
$c->select('
    `modUser`.*,
    `Profile`.`fullname` AS `fullname`,
    `Profile`.`email` AS `email`,
    `Profile`.`blocked` AS `blocked`
');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$users = $modx->getCollection('modUser',$c);

/* iterate through users */
$list = array();
foreach ($users as $user) {
	$userArray = $user->toArray();
    $userArray['blocked'] = $user->get('blocked') ? true : false;
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