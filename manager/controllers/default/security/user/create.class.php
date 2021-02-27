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
use MODX\Revolution\modSystemEvent;

/**
 * Loads the create user page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityUserCreateManagerController extends modManagerController
{
    public $onUserFormRender;

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('new_user');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.orm.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.user.js');
        $this->addHtml('<script>
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({ xtype: "modx-page-user-create" });
        });
        MODx.onUserFormRender = "'.$this->onUserFormRender.'";
        MODx.perm.set_sudo = '.($this->modx->hasPermission('set_sudo') ? 1 : 0).';
        // ]]>
        </script>');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/user/create.js');

    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
        $placeholders = [];

        /* invoke OnUserFormPrerender event */
        $onUserFormPrerender = $this->modx->invokeEvent('OnUserFormPrerender', [
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ]);
        if (is_array($onUserFormPrerender)) $onUserFormPrerender = implode('',$onUserFormPrerender);
        $placeholders['OnUserFormPrerender'] = $onUserFormPrerender;

        /* invoke OnUserFormRender event */
        $this->onUserFormRender = $this->modx->invokeEvent('OnUserFormRender', [
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ]);
        if (is_array($this->onUserFormRender)) $this->onUserFormRender = implode('',$this->onUserFormRender);
        $this->onUserFormRender = str_replace(['"',"\n","\r"], ['\"','',''],$this->onUserFormRender);

        $placeholders['OnUserFormRender'] = $this->onUserFormRender;

        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('create');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/user/create.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['user','setting','access'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Users';
    }
}
