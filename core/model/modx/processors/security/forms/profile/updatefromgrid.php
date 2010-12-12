<?php
/**
 * Update a FC Profile from grid
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

$_DATA = $modx->fromJSON($scriptProperties['data']);

if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('profile_err_ns'));
$profile = $modx->getObject('modFormCustomizationProfile',$_DATA['id']);
if ($profile == null) return $modx->error->failure($modx->lexicon('profile_err_nf'));

$profile->fromArray($_DATA);

if ($profile->save() === false) {
    return $modx->error->failure($modx->lexicon('profile_err_save'));
}

return $modx->error->success('',$profile);
