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
$modx->lexicon->load('category');

if (!$modx->hasPermission('save')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get category */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('category_err_ns'));
$category = $modx->getObject('modCategory',$_POST['id']);
if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));

/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$_POST['category'] = str_replace($invchars,'',$_POST['category']);

/* set fields */
$category->set('category',$_POST['category']);

/* save category */
if ($category->save() === false) {
	return $modx->error->failure($modx->lexicon('category_err_save'));
}

return $modx->error->success('',$category);