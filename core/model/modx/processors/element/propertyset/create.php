<?php
/**
 * Creates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
$modx->lexicon->load('propertyset');

/* make sure set with that name doesn't already exist */
$ae = $modx->getCount('modPropertySet',array(
    'name' => $_POST['name'],
));
if ($ae > 0) return $modx->error->failure($modx->lexicon('propertyset_err_ae'));

/* create property set */
$set = $modx->newObject('modPropertySet');
$set->set('name',$_POST['name']);
$set->set('description',$_POST['description']);

/* set category if specified */
if (isset($_POST['category']) && $_POST['category'] != 0 && $_POST['category'] != '') {
    $category = $modx->getObject('modCategory',$_POST['category']);
    if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));

    $set->set('category',$_POST['category']);
}


/* save set */
if ($set->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_create'));
}

return $modx->error->success();