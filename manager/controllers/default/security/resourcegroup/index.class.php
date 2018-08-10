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
 * Loads the resource group page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityResourceGroupManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission(array('resourcegroup_resource_list' => true,'resourcegroup_resource_edit' => true));
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.tree.resource.simple.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.tree.resource.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.resource.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/resourcegroup/list.js');
        $this->addHtml("<script>
            Ext.onReady(function() {
                MODx.add('modx-page-resource-groups');
            });</script>");
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {}

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('resource_groups');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Resource+Groups';
    }
}
