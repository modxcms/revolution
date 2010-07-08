<?php
/**
 * Change a user's password
 *
 * @param boolean $password_reset If true, will reset the password to new
 * parameters
 * @param string $password_old The old password
 * @param string $password_new The new password
 * @param string $password_confirm A confirmed version of the new password
 *
 * @package modx
 * @subpackage processors.security.profile
 */
if (!$modx->hasPermission('change_password')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* if changing the password */
if (md5($scriptProperties['password_old']) != $modx->user->get('password')) {
    return $modx->error->failure($modx->lexicon('user_err_password_invalid_old'));
}
if (empty($scriptProperties['password_new']) || strlen($scriptProperties['password_new']) < $modx->getOption('password_min_length',null,8)) {
    return $modx->error->failure($modx->lexicon('user_err_password_too_short'));
}

if (empty($scriptProperties['password_confirm']) || $scriptProperties['password_new'] != $scriptProperties['password_confirm']) {
    return $modx->error->failure($modx->lexicon('user_err_password_no_match'));
}

$modx->user->changePassword($scriptProperties['password_new'],$scriptProperties['password_old']);

/* log manager action */
$modx->logManagerAction('change_profile_password','modUser',$modx->user->get('id'));

return $modx->error->success($modx->lexicon('user_password_changed',array(
    'password' => $scriptProperties['password_new'],
)));