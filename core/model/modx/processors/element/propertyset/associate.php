<?php
/**
 * Associates a property set to an element, or creates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('save_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* get element */
if (!isset($scriptProperties['elementId']) || !isset($scriptProperties['elementType'])) {
    return $modx->error->failure($modx->lexicon('element_err_ns'));
}
$element = $modx->getObject($scriptProperties['elementType'],$scriptProperties['elementId']);

/* if creating new set */
if ($scriptProperties['propertyset_new'] == 'true') {
    if (!isset($scriptProperties['name']) || $scriptProperties['name'] == '') {
        return $modx->error->failure($modx->lexicon('propertyset_err_ns_name'));
    }

    /* if property set already exists with that name */
    $ae = $modx->getObject('modPropertySet',array('name' => $scriptProperties['name']));
    if ($ae != null) {
        return $modx->error->failure($modx->lexicon('propertyset_err_ae'));
    }

    $set = $modx->newObject('modPropertySet');
    $set->set('name',$scriptProperties['name']);
    $set->set('description',$scriptProperties['description']);

/* if associating existing set */
} elseif ($scriptProperties['propertyset'] != '0' && $scriptProperties['propertyset'] != '') {
    $set = $modx->getObject('modPropertySet',$scriptProperties['propertyset']);
    if ($set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

} else {
    return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
}

/* create element property set */
$pse = $modx->newObject('modElementPropertySet');
$pse->set('element',$scriptProperties['elementId']);
$pse->set('element_class',$scriptProperties['elementType']);
if (!$set->isNew()) {
    $pse->set('property_set',$set->get('id'));
}
$set->addMany($pse,'Elements');

if ($set->save() == false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_associate'));
}


return $modx->error->success('',$set);