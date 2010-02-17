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
if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$set = $modx->getObject('modPropertySet',$_POST['id']);
if (empty($set)) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

/* make sure set with that name doesn't already exist */
$alreadyExists = $modx->getCount('modPropertySet',array(
    'name' => $_POST['name'],
    'id:!=' => $_POST['id'],
));
if ($alreadyExists > 0) return $modx->error->failure($modx->lexicon('propertyset_err_ae'));

/* set category if specified */
if (isset($_POST['category'])) {
    if (!empty($_POST['category'])) {
        $category = $modx->getObject('modCategory',$_POST['category']);
        if (empty($category)) return $modx->error->failure($modx->lexicon('category_err_nf'));

        $set->set('category',$_POST['category']);
    } else {
        $set->set('category',0);
    }
}

/* create property set */
$set->set('name',$_POST['name']);
$set->set('description',$_POST['description']);

/* save set */
if ($set->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_create'));
}

return $modx->error->success('',$set);