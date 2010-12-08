<?php
/**
 * Remove multiple FC profiles
 *
 * @package modx
 * @subpackage processors.security.forms.profiles
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

    if ($profile->remove() === false) {
        return $modx->error->failure($modx->lexicon('profile_err_remove'));
    }
}

return $modx->error->success();
