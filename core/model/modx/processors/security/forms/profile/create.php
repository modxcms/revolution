<?php
/**
 * Create a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

/* create new profile object */
$profile = $modx->newObject('modFormCustomizationProfile');
$profile->fromArray($scriptProperties);

/* save set */
if ($profile->save() === false) {
    return $modx->error->failure($modx->lexicon('profile_err_save'));
}

return $modx->error->success('',$profile);
