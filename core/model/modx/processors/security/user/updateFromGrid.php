<?php
/**
 * Update a user from a grid
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'save_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$_DATA = $modx->fromJSON($_POST['data']);

$user = $modx->getObject('modUser',$_DATA['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_not_found'));

$up = $user->getOne('modUserProfile');
if ($up == null) return $modx->error->failure($modx->lexicon('user_profile_err_not_found'));

$up->fromArray($_DATA);

if ($up->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}

/* log manager action */
$modx->logManagerAction('user_update','modUser',$user->get('id'));

return $modx->error->success();