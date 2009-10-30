<?php
/**
 * Creates a category.
 *
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
if (!$modx->hasPermission('create')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

/* prevent empty names */
if (empty($_POST['category'])) $modx->error->addField('category',$modx->lexicon('category_err_ns'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create category object */
$category = $modx->newObject('modCategory');
$category->fromArray($_POST);

/* save category */
if ($category->save() == false) {
    $modx->error->checkValidation($category);
    return $modx->error->failure($modx->lexicon('category_err_create'));
}

/* log manager action */
$modx->logManagerAction('category_create','modCategory',$category->get('id'));

return $modx->error->success();