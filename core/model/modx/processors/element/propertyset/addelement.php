<?php
/**
 * Adds an element to a propertyset
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('save_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

if (!isset($_POST['element_class']) || !isset($_POST['element'])) {
    return $modx->error->failure($modx->lexicon('element_err_ns'));
}
$elementClass = $_POST['element_class'];
$elementId = $_POST['element'];

/* grab element */
$element = $modx->getObject($elementClass,$elementId);
if ($element == null) $modx->error->failure($modx->lexicon('element_err_nf'));

/* grab the modPropertySet */
if (!isset($_POST['propertyset']) || $_POST['propertyset'] == '') {
    return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
}
$set = $modx->getObject('modPropertySet',$_POST['propertyset']);
if ($set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nfs',array('id' => $_REQUEST['id'])));

$pse = $modx->newObject('modElementPropertySet');
$pse->set('element',$elementId);
$pse->set('element_class',$elementClass);
$pse->set('property_set',$set->get('id'));

if ($pse->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_element_add'));
}

return $modx->error->success();