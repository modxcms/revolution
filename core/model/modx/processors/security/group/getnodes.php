<?php
/**
 * Get the user groups in tree node format
 *
 * @param string $id The parent ID
 *
 * @package modx
 * @subpackage processors.security.group
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : str_replace('n_ug_','',$_REQUEST['id']);

$g = $modx->getObject('modUserGroup',$_REQUEST['id']);
$groups = $modx->getCollection('modUserGroup',array('parent' => $_REQUEST['id']));

$da = array();
foreach ($groups as $group) {
	$da[] = array(
		'text' => $group->get('name'),
		'id' => 'n_ug_'.$group->get('id'),
		'leaf' => 0,
		'type' => 'usergroup',
		'cls' => 'folder',
        'menu' => array(
            'items' => array(
                array(
                    'text' => $modx->lexicon('add_user_to_group'),
                    'handler' => 'function(itm,e) {
                        this.addUser(itm,e);
                    }',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('create_user_group'),
                    'handler' => 'function(itm,e) {
                        this.create(itm,e);
                    }',
                ),
                array(
                    'text' => $modx->lexicon('user_group_update'),
                    'handler' => 'function(itm,e) {
                        this.update(itm,e);
                    }',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('delete_user_group'),
                    'handler' => 'function(itm,e) {
                        this.remove(itm,e);
                    }',
                ),
            ),
        ),
	);
}
if ($g != null) {
	$users = $g->getUsersIn();
	foreach ($users as $user) {
		$da[] = array(
			'text' => $user->get('username'),
			'id' => 'n_user_'.$user->get('id'),
			'leaf' => 1,
			'type' => 'user',
			'cls' => '',
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('remove_user_from_group'),
                        'handler' => 'function(itm,e) {
                            this.removeUser(itm,e);
                        }',
                    ),
                ),
            ),
		);
	}
}

return $this->toJSON($da);