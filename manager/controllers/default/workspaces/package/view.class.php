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
 * Loads the workspace package builder
 *
 * @package modx
 * @subpackage manager.controllers
 */
class WorkspacesPackageViewManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('workspaces');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/workspace/combos.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package/package.versions.grid.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package/package.panel.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package/index.js');
        $this->addHtml("<script>
            Ext.onReady(function() {
                //MODx.load({ xtype: 'modx-page-package' });
                MODx.add('modx-page-package');
            });</script>");
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {}

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('package_view');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return;
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['workspace','namespace'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Package+Management';
    }
}
