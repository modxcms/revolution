<?php
/**
 * Duplicate a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('set_err_ns'));
$oldSet = $modx->getObject('modFormCustomizationSet',$scriptProperties['id']);
if ($oldSet == null) return $modx->error->failure($modx->lexicon('set_err_nf'));

$newSet = $modx->newObject('modFormCustomizationSet');
$newSet->fromArray($oldSet->toArray());
$newSet->set('constraint_class','modResource');
$newSet->set('active',false);

if ($newSet->save() === false) {
    return $modx->error->failure($modx->lexicon('set_err_save'));
}

/* duplicate old rules */
$rules = $oldSet->getMany('Rules');
foreach ($rules as $rule) {
    $newRule = $modx->newObject('modActionDom');
    $newRule->fromArray($rule->toArray());
    $newRule->set('set',$newSet->get('id'));
    $newRule->save();
}

return $modx->error->success('',$newSet);
