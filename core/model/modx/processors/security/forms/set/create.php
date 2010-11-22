<?php
/**
 * Create a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

/* create new set object */
$set = $modx->newObject('modFormCustomizationSet');
$set->fromArray($scriptProperties);
$set->set('constraint_class','modResource');
$set->set('action',$scriptProperties['action_id']);

/* save set */
if ($set->save() === false) {
    return $modx->error->failure($modx->lexicon('set_err_save'));
}

return $modx->error->success('',$set);
