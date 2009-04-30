<?php
/**
 * Updates a  property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
$modx->lexicon->load('propertyset','category');

$_POST['copyels'] = !isset($_POST['copyels']) ? 0 : 1;

/* get property set */
if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$old_set = $modx->getObject('modPropertySet',$_POST['id']);
if ($old_set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

/* create new property set */
$set = $modx->newObject('modPropertySet');
$set->fromArray($old_set->toArray());
$set->set('name',$_POST['new_name']);

/* if set, copy element associations */
if ($_POST['copyels']) {
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

return $modx->error->success();