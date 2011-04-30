<?php
/**
 * Creates a category.
 *
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
if (!$modx->hasPermission('new_category')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

/* prevent empty names */
if (empty($scriptProperties['category'])) $modx->error->addField('category',$modx->lexicon('category_err_ns'));

$alreadyExists = $modx->getObject('modCategory',array('category' => $scriptProperties['category']));
if ($alreadyExists) {
    $modx->error->addField('category',$modx->lexicon('category_err_ae'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create category object */
$category = $modx->newObject('modCategory');
$category->fromArray($scriptProperties);

/* save category */
if ($category->save() == false) {
    $modx->error->checkValidation($category);
    return $modx->error->failure($modx->lexicon('category_err_create'));
}

/* log manager action */
$modx->logManagerAction('category_create','modCategory',$category->get('id'));

return $modx->error->success();