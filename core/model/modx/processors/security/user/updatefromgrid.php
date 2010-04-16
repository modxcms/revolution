<?php
/**
 * Update a user from a grid
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('save_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get user */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_DATA['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

$user->fromArray($_DATA);

/* add a profile if missing and set fields */
$profile = $user->getOne('Profile');
if (empty($profile)) {
    $profile = $modx->newObject('modUserProfile');
    $profile->set('internalKey',$user->get('id'));
}
$profile->fromArray($_DATA);

/* save user and profile */
if ($user->save() == false || $profile->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}

/* log manager action */
$modx->logManagerAction('user_update','modUser',$user->get('id'));

return $modx->error->success();