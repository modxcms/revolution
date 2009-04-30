<?php
/**
 * Creates a category.
 *
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
$modx->lexicon->load('category');

$category = $modx->newObject('modCategory');
$category->fromArray($_POST);

if ($category->save() == false) {
    $modx->error->checkValidation($category);
    return $modx->error->failure($modx->lexicon('category_err_create'));
}

/* log manager action */
$modx->logManagerAction('category_create','modCategory',$category->get('id'));

return $modx->error->success();