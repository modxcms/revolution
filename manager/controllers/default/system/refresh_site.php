<?php
/**
 * Refreshes the site cache
 *
 * @package modx
 * @subpackage manager.controllers
 */
if (!$modx->hasPermission('empty_cache')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* invoke OnBeforeCacheUpdate event */
$modx->invokeEvent('OnBeforeCacheUpdate');

$results= array();
$modx->cacheManager->refresh(array(), $results);

/* invoke OnSiteRefresh event */
$modx->invokeEvent('OnSiteRefresh');

$num_rows_pub = isset($results['publishing']['published']) ? $results['publishing']['published'] : 0;
$num_rows_unpub = isset($results['publishing']['unpublished']) ? $results['publishing']['unpublished'] : 0;
$modx->smarty->assign('published',$modx->lexicon('refresh_published',array('num' => $num_rows_pub)));
$modx->smarty->assign('unpublished',$modx->lexicon('refresh_unpublished',array('num' => $num_rows_unpub)));

$modx->smarty->assign('results', $results);

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/refresh_site.tpl');