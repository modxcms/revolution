<?php
/**
 * Update all users from JSON data
 *
 * @param json $data The JSON-encoded data rows
 *
 * @package modx
 * @subpackage processors.security.user
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'save_user' => true))) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

foreach ($_DATA as $userdata) {
	$user = $modx->getObject('modUser',$userdata['id']);
	if ($user == null) continue;

	$up = $user->getOne('modUserProfile');
	$up->set('gender',$userdata['gender']);
	$up->set('fullname',$userdata['fullname']);
	$up->set('blocked',$userdata['blocked']);
	$up->set('email',$userdata['email']);

	if (!$up->save()) return $modx->error->failure($modx->lexicon('user_err_save'));
}

return $modx->error->success();