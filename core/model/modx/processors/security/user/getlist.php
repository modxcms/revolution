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
if (!$modx->hasPermission('view_user')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'username');
if ($sort == 'username_link') $sort = 'username';
if ($sort == 'id') $sort = 'modUser.id';
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* query for users */
$c = $modx->newQuery('modUser');
$c->leftJoin('modUserProfile','Profile');
if (!empty($scriptProperties['query'])) {
    $c->where(array('modUser.username:LIKE' => '%'.$scriptProperties['query'].'%'));
    $c->orCondition(array('Profile.fullname:LIKE' => '%'.$scriptProperties['query'].'%'));
    $c->orCondition(array('Profile.email:LIKE' => '%'.$scriptProperties['query'].'%'));
}
if (!empty($scriptProperties['usergroup'])) {
    $c->innerJoin('modUserGroupMember','UserGroupMembers');
    $c->where(array(
        'UserGroupMembers.user_group' => $scriptProperties['usergroup'],
    ));
}
$count = $modx->getCount('modUser',$c);
$c->select(array(
    'modUser.*',
    'Profile.fullname',
    'Profile.email',
    'Profile.blocked',
));
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$users = $modx->getCollection('modUser',$c);

/* iterate through users */
$list = array();
foreach ($users as $user) {
    $userArray = $user->toArray();
    $userArray['blocked'] = $user->get('blocked') ? true : false;
    $userArray['cls'] = 'pupdate premove';
    unset($userArray['password'],$userArray['cachepwd']);
    $list[] = $userArray;
}
return $this->outputArray($list,$count);