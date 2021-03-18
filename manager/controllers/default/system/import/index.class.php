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
 * Loads the Import Resources page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemImportManagerController extends modManagerController {
    /**
    * Check for any permissions or requirements to load page
    * @return bool
    */
    public function checkPermissions() {
        return $this->modx->hasPermission('import_static');
    }

    /**
    * Register custom CSS/JS for the page
    * @return void
    */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.tree.resource.simple.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.panel.import.resources.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/system/import/resource.js');
        $this->addHtml("<script>
            Ext.onReady(function() {
                MODx.add('modx-page-import-resource');
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
        return $this->modx->lexicon('import_site');
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
        return ['import'];
    }
}
