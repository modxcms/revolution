<?php
/**
 * Duplicates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('new_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','category');

$scriptProperties['copyels'] = !isset($scriptProperties['copyels']) ? 0 : 1;

if (empty($scriptProperties['new_name'])) {
    $modx->error->addField('new_name',$modx->lexicon('propertyset_err_ns_name'));
}

/* get property set */
if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$oldSet = $modx->getObject('modPropertySet',$scriptProperties['id']);
if ($oldSet == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create new property set */
$set = $modx->newObject('modPropertySet');
$set->fromArray($oldSet->toArray());
$set->set('name',$scriptProperties['new_name']);

/* if set, copy element associations */
if ($scriptProperties['copyels']) {
    $els = $oldSet->getMany('Elements');
    $pses = array();
    foreach ($els as $el) {
        $pse = $modx->newObject('modElementPropertySet');
        $pse->set('element_class',$el->get('element_class'));
        $pse->set('element',$el->get('element'));
        $pses[] = $pse;
    }
    $set->addMany($pses);
}

/* save set */
if ($set->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_create'));
}

return $modx->error->success('',$set);