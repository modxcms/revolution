<?php
/**
 * Activate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('profile_err_ns'));
$profile = $modx->getObject('modFormCustomizationProfile',$scriptProperties['id']);
if ($profile == null) return $modx->error->failure($modx->lexicon('profile_err_nf'));

$profile->set('active',true);

if ($profile->save() === false) {
    return $modx->error->failure($modx->lexicon('profile_err_save'));
}

return $modx->error->success('',$profile);
