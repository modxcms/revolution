<?php
/**
 * Creates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('new_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

if (empty($scriptProperties['name'])) {
    $modx->error->addField('name',$modx->lexicon('propertyset_err_ns_name'));
}
/* make sure set with that name doesn't already exist */
$alreadyExists = $modx->getCount('modPropertySet',array(
    'name' => $scriptProperties['name'],
));
if ($alreadyExists > 0) return $modx->error->addField('name',$modx->lexicon('propertyset_err_ae'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create property set */
$set = $modx->newObject('modPropertySet');
$set->set('name',$scriptProperties['name']);
$set->set('description',$scriptProperties['description']);

/* set category if specified */
if (isset($scriptProperties['category']) && $scriptProperties['category'] != 0 && $scriptProperties['category'] != '') {
    $category = $modx->getObject('modCategory',$scriptProperties['category']);
    if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));

    $set->set('category',$scriptProperties['category']);
}


/* save set */
if ($set->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_create'));
}

return $modx->error->success('',$set);