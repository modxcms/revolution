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
 * Loads the profile page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityProfileManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('change_profile');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.recent.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/profile/update.js');
        $this->addHtml('
        <script>
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-profile"
                ,user: "'.$this->modx->user->get('id').'"
            });
        });
        // ]]>
        </script>');
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
        if($this->modx->user == null) {
            return $this->modx->lexicon('user_err_nf');
        } else {
            return $this->modx->lexicon('profile').': '.htmlentities($this->modx->user->get('username'));
        }
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
        return ['access','user'];
    }
}
