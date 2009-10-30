<?php
/**
 * Gets a category.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

/* get category */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('category_err_ns'));
$category = $modx->getObject('modCategory',$_REQUEST['id']);
if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));

return $modx->error->success('',$category);