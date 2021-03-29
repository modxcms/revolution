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
 * Loads the usergroup update page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityUserGroupUpdateManagerController extends modManagerController {
    /** @var modUserGroup $userGroup */
    public $userGroup;
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('usergroup_view');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.settings.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.settings.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.context.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.category.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.source.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.namespace.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.user.group.js');
        $canEditUsers = $this->modx->hasPermission('usergroup_user_edit') ? 1 : 0;
        $canListUsers = $this->modx->hasPermission('usergroup_user_list') ? 1 : 0;
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/usergroup/update.js');
        $this->addHtml('<script>
        MODx.perm.usergroup_user_edit = '.$canEditUsers.';
        MODx.perm.usergroup_user_list = '.$canListUsers.';
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-user-group-update"
                 ,record: '.$this->modx->toJSON($this->userGroup->toArray()).'
            });
        });
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        if (empty($scriptProperties['id']) || strlen($scriptProperties['id']) !== strlen((integer)$scriptProperties['id'])) {
            $this->userGroup = $this->modx->newObject('modUserGroup');
            $this->userGroup->set('id',0);
            $this->userGroup->set('name',$this->modx->lexicon('anonymous'));
        } else {
            $this->userGroup = $this->modx->getObject('modUserGroup', array('id' => $scriptProperties['id']));
            if (empty($this->userGroup)) {
                $this->failure($this->modx->lexicon('usergroup_err_nf'));
            }
        }
        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        $ugName = $this->userGroup ? $this->userGroup->get('name') : $this->modx->lexicon('anonymous');
        return $this->modx->lexicon('user_group').': '.$ugName;
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
        return array('user','access','policy','context','setting');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'User+Groups';
    }
}
