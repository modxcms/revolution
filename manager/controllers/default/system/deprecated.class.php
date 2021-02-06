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

class SystemDeprecatedManagerController extends modManagerController {
    public $logArray = [];

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('error_log_view');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.grid.deprecated.log.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.panel.deprecated.log.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/system/deprecated.log.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.load({
              xtype: "modx-page-deprecated-log"
            });
        });
        </script>');
    }

    /**
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('deprecated_log');
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
        return ['system_events'];
    }
}
