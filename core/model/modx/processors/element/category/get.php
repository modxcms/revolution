<?php
/**
 * Gets a category.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
if (!$modx->hasPermission('view_category')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

/* get category */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('category_err_ns'));
$category = $modx->getObject('modCategory',$scriptProperties['id']);
if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));

if (!$category->checkPolicy('view')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->error->success('',$category);