<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modManagerController;

/**
 * Refreshes the site cache
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemRefreshSiteManagerController extends modManagerController
{
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('empty_cache');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     * @return string
     */
    public function process(array $scriptProperties = [])
    {
        /* invoke OnBeforeCacheUpdate event */
        $this->modx->invokeEvent('OnBeforeCacheUpdate');

        $results = [];
        $this->modx->cacheManager->refresh([], $results);

        /* invoke OnSiteRefresh event */
        $this->modx->invokeEvent('OnSiteRefresh');

        $num_rows_pub = $results['publishing']['published'] ?? 0;
        $num_rows_unpub = $results['publishing']['unpublished'] ?? 0;
        $this->modx->smarty->assign('published', $this->modx->lexicon('refresh_published', ['num' => $num_rows_pub]));
        $this->modx->smarty->assign('unpublished', $this->modx->lexicon('refresh_unpublished', ['num' => $num_rows_unpub]));
        $this->modx->smarty->assign('results', $results);

        $this->checkFormCustomizationRules();

        return $this->modx->smarty->fetch('system/refresh_site.tpl');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('empty_cache');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs()
    {
        return;
    }

    /**
     * Return the location of the template file
     * @return string|null
     */
    public function getTemplateFile()
    {
        return null;
    }
}
