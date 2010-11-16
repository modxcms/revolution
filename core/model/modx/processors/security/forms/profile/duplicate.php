<?php
/**
 * Duplicate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('profile_err_ns'));
$oldProfile = $modx->getObject('modFormCustomizationProfile',$scriptProperties['id']);
if ($oldProfile == null) return $modx->error->failure($modx->lexicon('profile_err_nf'));

$newProfile = $modx->newObject('modFormCustomizationProfile');
$newProfile->set('name',$modx->lexicon('duplicate_of',array('name' => $oldProfile->get('name'))));
$newProfile->set('active',false);

if ($newProfile->save() === false) {
    return $modx->error->failure($modx->lexicon('profile_err_save'));
}

/* duplicate old user group access */
$usergroups = $modx->getCollection('modFormCustomizationProfileUserGroup',array(
    'profile' => $oldProfile->get('id'),
));
foreach ($usergroups as $usergroup) {
    $nug = $modx->newObject('modFormCustomizationProfileUserGroup');
    $nug->set('usergroup',$usergroup->get('usergroup'));
    $nug->set('profile',$newProfile->get('id'));
    $nug->save();
}

/* duplicate old sets/rules */
$sets = $oldProfile->getMany('Sets');
foreach ($sets as $set) {
    $newSet = $modx->newObject('modFormCustomizationSet');
    $newSet->fromArray($set->toArray());
    $newSet->set('profile',$newProfile->get('id'));
    $newSet->save();

    $rules = $set->getMany('Rules');
    foreach ($rules as $rule) {
        $newRule = $modx->newObject('modActionDom');
        $newRule->fromArray($rule->toArray());
        $newRule->set('set',$newSet->get('id'));
        $newRule->save();
    }
}

return $modx->error->success('',$newProfile);
