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

/* get property set */
if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$old_set = $modx->getObject('modPropertySet',$scriptProperties['id']);
if ($old_set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

/* create new property set */
$set = $modx->newObject('modPropertySet');
$set->fromArray($old_set->toArray());
$set->set('name',$scriptProperties['new_name']);

/* if set, copy element associations */
if ($scriptProperties['copyels']) {
    $els = $old_set->getMany('Elements');
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