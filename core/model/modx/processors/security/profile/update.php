<?php
/**
 * Update a user profile
 *
 * @package modx
 * @subpackage processors.security.profile
 */
if (!$modx->hasPermission('change_profile')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get profile */
$profile = $modx->user->getOne('Profile');
if ($profile == null) return $modx->error->failure($modx->lexicon('user_profile_err_not_found'));

/* format and set data */
$scriptProperties['dob'] = strtotime($scriptProperties['dob']);
$profile->fromArray($scriptProperties);

/* save profile */
if ($profile->save() == false) {
    return $modx->error->failure($modx->lexicon('user_profile_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_profile','modUser',$modx->user->get('id'));

return $modx->error->success($modx->lexicon('success'));