<?php
/**
 * Deactivate multiple FC Profiles
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['profiles'])) {
    return $modx->error->failure($modx->lexicon('profile_err_ns'));
}

$profileIds = explode(',',$scriptProperties['profiles']);

foreach ($profileIds as $profileId) {
    $profile = $modx->getObject('modFormCustomizationProfile',$profileId);
    if ($profile == null) continue;

    $profile->set('active',false);

    if ($profile->save() === false) {
        return $modx->error->failure($modx->lexicon('profile_err_save'));
    }
}

return $modx->error->success();
