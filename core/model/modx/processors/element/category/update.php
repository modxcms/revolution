<?php
/**
 * Update a category.
 *
 * @param integer $id The ID of the category.
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
if (!$modx->hasPermission('save')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

/* get category */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('category_err_ns'));
$category = $modx->getObject('modCategory',$_POST['id']);
if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));

/* set fields */
$category->fromArray($_POST);

/* save category */
if ($category->save() === false) {
	return $modx->error->failure($modx->lexicon('category_err_save'));
}

return $modx->error->success('',$category);