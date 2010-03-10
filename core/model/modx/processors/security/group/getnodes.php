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

$scriptProperties['id'] = !isset($scriptProperties['id']) ? 0 : str_replace('n_ug_','',$scriptProperties['id']);

$showAnonymous = $modx->getOption('showAnonymous',$scriptProperties,true);

$usergroup = $modx->getObject('modUserGroup',$scriptProperties['id']);
$groups = $modx->getCollection('modUserGroup',array('parent' => $scriptProperties['id']));

$list = array();
if ($showAnonymous && empty($scriptProperties['id'])) {
    $menu = array();
    $menu[] = array(
        'text' => $modx->lexicon('user_group_update'),
        'handler' => 'function(itm,e) {
            this.update(itm,e);
        }',
    );
    $list[] = array(
        'text' => '('.$modx->lexicon('anonymous').')',
        'id' => 'n_ug_0',
        'leaf' => true,
        'type' => 'usergroup',
        'cls' => 'icon-group',
        'menu' => array(
            'items' => $menu,
        ),
    );
}
foreach ($groups as $group) {
    $menu = array();
    $menu[] = array(
        'text' => $modx->lexicon('user_group_user_add'),
        'handler' => 'function(itm,e) {
            this.addUser(itm,e);
        }'
    );
    $menu[] = '-';
    $menu[] = array(
        'text' => $modx->lexicon('user_group_create'),
        'handler' => 'function(itm,e) {
            this.create(itm,e);
        }',
    );
    $menu[] = array(
        'text' => $modx->lexicon('user_group_update'),
        'handler' => 'function(itm,e) {
            this.update(itm,e);
        }',
    );
    if ($group->get('id') != 1) {
        $menu[] = '-';
        $menu[] = array(
            'text' => $modx->lexicon('user_group_remove'),
            'handler' => 'function(itm,e) {
                this.remove(itm,e);
            }',
        );
    }

	$list[] = array(
		'text' => $group->get('name'),
		'id' => 'n_ug_'.$group->get('id'),
		'leaf' => 0,
		'type' => 'usergroup',
		'cls' => 'icon-group',
        'menu' => array(
            'items' => $menu,
        ),
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
        $menu = array();
        $menu[] = array(
            'text' => $modx->lexicon('user_group_user_remove'),
            'handler' => 'function(itm,e) {
                this.removeUser(itm,e);
            }',
        );

		$list[] = array(
			'text' => $user->get('username'),
			'id' => 'n_user_'.$user->get('id').'_'.$usergroup->get('id'),
			'leaf' => 1,
			'type' => 'user',
			'cls' => 'icon-user',
            'menu' => array('items' => $menu),
		);
	}
}

return $this->toJSON($list);