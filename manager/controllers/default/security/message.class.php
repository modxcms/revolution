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
 * Loads message management
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityMessageManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('messages');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.message.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/message/list.js');
        $this->addHtml('<script>
            Ext.onReady(function() {
                MODx.perm.view_user = '.($this->modx->hasPermission('view_user') ? 1 : 0).';
                MODx.perm.view_role = '.($this->modx->hasPermission('view_role') ? 1 : 0).';
                MODx.perm.view_usergroup = '.($this->modx->hasPermission('usergroup_view') ? 1 : 0).';
                MODx.load({
                    xtype: "modx-page-messages"
                });
            });</script>');
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
        return $this->modx->lexicon('messages');
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
        return array('user','messages');
    }
}
