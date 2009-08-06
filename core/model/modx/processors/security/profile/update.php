<?php
/**
 * Update a user profile
 *
 * @package modx
 * @subpackage processors.security.profile
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('change_profile')) return $modx->error->failure($modx->lexicon('permission_denied'));

$profile = $modx->user->getOne('Profile');
if ($profile == null) return $modx->error->failure($modx->lexicon('user_profile_err_not_found'));

$_POST['dob'] = strtotime($_POST['dob']);
$profile->fromArray($_POST);

if ($profile->save() == false) {
    return $modx->error->failure($modx->lexicon('user_profile_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_profile','modUser',$modx->user->get('id'));

return $modx->error->success($modx->lexicon('success'));