<?php
/**
 * Associates a property set to an element, or creates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('create')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* get element */
if (!isset($_POST['elementId']) || !isset($_POST['elementType'])) {
    return $modx->error->failure($modx->lexicon('element_err_ns'));
}
$element = $modx->getObject($_POST['elementType'],$_POST['elementId']);

/* if creating new set */
if ($_POST['propertyset_new'] == 'true') {
    if (!isset($_POST['name']) || $_POST['name'] == '') {
        return $modx->error->failure($modx->lexicon('propertyset_err_ns_name'));
    }

    /* if property set already exists with that name */
    $ae = $modx->getObject('modPropertySet',array('name' => $_POST['name']));
    if ($ae != null) {
        return $modx->error->failure($modx->lexicon('propertyset_err_ae'));
    }

    $set = $modx->newObject('modPropertySet');
    $set->set('name',$_POST['name']);
    $set->set('description',$_POST['description']);

/* if associating existing set */
} elseif ($_POST['propertyset'] != '0' && $_POST['propertyset'] != '') {
    $set = $modx->getObject('modPropertySet',$_POST['propertyset']);
    if ($set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

} else {
    return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
}

/* create element property set */
$pse = $modx->newObject('modElementPropertySet');
$pse->set('element',$_POST['elementId']);
$pse->set('element_class',$_POST['elementType']);
if (!$set->isNew()) {
    $pse->set('property_set',$set->get('id'));
}
$set->addMany($pse,'Elements');

if ($set->save() == false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_associate'));
}


return $modx->error->success('',$set);