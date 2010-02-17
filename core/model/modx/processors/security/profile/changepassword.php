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
if (isset($_POST['password_reset'])) {
    if (md5($_POST['password_old']) != $modx->user->get('password')) {
        return $modx->error->failure($modx->lexicon('user_err_password_invalid_old'));
    }
    if (empty($_POST['password_new']) || strlen($_POST['password_new']) < $modx->getOption('password_min_length',null,8)) {
        return $modx->error->failure($modx->lexicon('user_err_password_too_short'));
    }

    if (empty($_POST['password_confirm']) || $_POST['password_new'] != $_POST['password_confirm']) {
        return $modx->error->failure($modx->lexicon('user_err_passwords_no_match'));
    }

    $modx->user->set('password',md5($_POST['password_new']));
    $modx->user->save();

    $modx->invokeEvent('OnManagerChangePassword',array(
        'user' => &$modx->user,
        'newPassword' => $_POST['password_new'],
    ));
}


/* log manager action */
$modx->logManagerAction('change_profile_password','modUser',$modx->user->get('id'));

return $modx->error->success($modx->lexicon('success'));