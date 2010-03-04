<?php
/**
 * Updates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('save_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','category');

/* get property set */
if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$set = $modx->getObject('modPropertySet',$scriptProperties['id']);
if (empty($set)) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

/* make sure set with that name doesn't already exist */
$alreadyExists = $modx->getCount('modPropertySet',array(
    'name' => $scriptProperties['name'],
    'id:!=' => $scriptProperties['id'],
));
if ($alreadyExists > 0) return $modx->error->failure($modx->lexicon('propertyset_err_ae'));

/* set category if specified */
if (isset($scriptProperties['category'])) {
    if (!empty($scriptProperties['category'])) {
        $category = $modx->getObject('modCategory',$scriptProperties['category']);
        if (empty($category)) return $modx->error->failure($modx->lexicon('category_err_nf'));

        $set->set('category',$scriptProperties['category']);
    } else {
        $set->set('category',0);
    }
}

/* create property set */
$set->set('name',$scriptProperties['name']);
$set->set('description',$scriptProperties['description']);

/* save set */
if ($set->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_create'));
}

return $modx->error->success('',$set);