<?php
/**
 * Update a user from a grid
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission(array('access_permissions' => true, 'save_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');


$_DATA = $modx->fromJSON($_POST['data']);

$user = $modx->getObject('modUser',$_DATA['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_not_found'));

$user->fromArray($_DATA);

$up = $user->getOne('Profile');
if ($up == null) return $modx->error->failure($modx->lexicon('user_profile_err_not_found'));

$up->fromArray($_DATA);

if ($user->save() == false || $up->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}


/* invoke OnManagerSaveUser event */
$modx->invokeEvent('OnManagerSaveUser',array(
    'mode' => 'upd',
    'user' => &$user,
));

/* log manager action */
$modx->logManagerAction('user_update','modUser',$user->get('id'));

return $modx->error->success();