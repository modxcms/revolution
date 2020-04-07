<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Refreshes the site cache
 *
 * @package modx
 * @subpackage manager.controllers
 */
if (!$modx->hasPermission('empty_cache')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* invoke OnBeforeCacheUpdate event */
$modx->invokeEvent('OnBeforeCacheUpdate');

$results= [];
$modx->cacheManager->refresh([], $results);

/* invoke OnSiteRefresh event */
$modx->invokeEvent('OnSiteRefresh');

$num_rows_pub = isset($results['publishing']['published']) ? $results['publishing']['published'] : 0;
$num_rows_unpub = isset($results['publishing']['unpublished']) ? $results['publishing']['unpublished'] : 0;
$modx->smarty->assign('published',$modx->lexicon('refresh_published', ['num' => $num_rows_pub]));
$modx->smarty->assign('unpublished',$modx->lexicon('refresh_unpublished', ['num' => $num_rows_unpub]));

$modx->smarty->assign('results', $results);

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/refresh_site.tpl');
