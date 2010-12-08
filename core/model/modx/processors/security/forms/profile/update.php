<?php
/**
 * Update a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('profile_err_ns'));
$profile = $modx->getObject('modFormCustomizationProfile',$scriptProperties['id']);
if ($profile == null) return $modx->error->failure($modx->lexicon('profile_err_nf'));

$scriptProperties['active'] = !empty($scriptProperties['active']) ? true : false;
$profile->fromArray($scriptProperties);

/* get usergroups */
if (isset($scriptProperties['usergroups'])) {
    /* erase old fpug records */
    $fcpugs = $modx->getCollection('modFormCustomizationProfileUserGroup',array(
        'profile' => $profile->get('id'),
    ));
    foreach ($fcpugs as $fcpug) { $fcpug->remove(); }

    /* reassign */
    $usergroups = $modx->fromJSON($scriptProperties['usergroups']);
    foreach ($usergroups as $ug) {
        if (empty($ug)) continue;
        $usergroup = $modx->getObject('modUserGroup',$ug['id']);
        if (empty($usergroup)) continue;

        $fcpug = $modx->newObject('modFormCustomizationProfileUserGroup');
        $fcpug->set('usergroup',$usergroup->get('id'));
        $fcpug->set('profile',$profile->get('id'));
        $fcpug->save();
    }
}

if ($profile->save() === false) {
    return $modx->error->failure($modx->lexicon('profile_err_save'));
}

return $modx->error->success('',$profile);
