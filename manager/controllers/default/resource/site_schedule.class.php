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
 * Loads the site schedule
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ResourceSiteScheduleManagerController extends modManagerController
{
    /**
     * Check for any permissions or requirements to load page
     *
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_document');
    }


    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $mgrUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.schedule.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/schedule.js');
        $this->addHtml("<script>Ext.onReady(function() {MODx.add('modx-page-resource-schedule')});</script>");
    }


    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     *
     * @return mixed
     */
    public function process(array $scriptProperties = [])
    {
        return '';
    }


    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('site_schedule');
    }


    /**
     * Return the location of the template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return '';
    }


    /**
     * Specify the language topics to load
     *
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['resource'];
    }
}
