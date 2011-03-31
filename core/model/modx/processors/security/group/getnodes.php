<?php
/**
 * Get the user groups in tree node format
 *
 * @param string $id The parent ID
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get default properties */
$scriptProperties['id'] = !isset($scriptProperties['id']) ? 0 : str_replace('n_ug_','',$scriptProperties['id']);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$showAnonymous = $modx->getOption('showAnonymous',$scriptProperties,true);

/* get current User Group */
if (!empty($scriptProperties['id'])) {
    $usergroup = $modx->getObject('modUserGroup',$scriptProperties['id']);
} else { $usergroup = null; }

/* build query */
$c = $modx->newQuery('modUserGroup');
$c->where(array(
    'parent' => $scriptProperties['id'],
));
$c->sortby($sort,$dir);
$groups = $modx->getCollection('modUserGroup',$c);

$list = array();
if ($showAnonymous && empty($scriptProperties['id'])) {
    $cls = 'icon-group';
    $cls .= ' pupdate';
    $list[] = array(
        'text' => '('.$modx->lexicon('anonymous').')',
        'id' => 'n_ug_0',
        'leaf' => true,
        'type' => 'usergroup',
        'cls' => $cls,
    );
}
foreach ($groups as $group) {
    $cls = 'icon-group padduser pcreate pupdate';
    if ($group->get('id') != 1) {
        $cls .= ' premove';
    }

    $list[] = array(
        'text' => $group->get('name').' ('.$group->get('id').')',
        'id' => 'n_ug_'.$group->get('id'),
        'leaf' => false,
        'type' => 'usergroup',
        'qtip' => $group->get('description'),
        'cls' => $cls,
    );
}
if ($usergroup) {
    $c = $modx->newQuery('modUser');
    $c->innerJoin('modUserGroupMember','UserGroupMembers');
    $c->where(array(
        'UserGroupMembers.user_group' => $usergroup->get('id'),
    ));
    $users = $modx->getCollection('modUser',$c);
    foreach ($users as $user) {
        $list[] = array(
            'text' => $user->get('username'),
            'id' => 'n_user_'.$user->get('id').'_'.$usergroup->get('id'),
            'leaf' => true,
            'type' => 'user',
            'cls' => 'icon-user',
        );
    }
}

return $this->toJSON($list);