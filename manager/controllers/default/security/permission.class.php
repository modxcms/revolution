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
 * Loads the access permissions page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityPermissionManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.access.policy.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.access.policy.template.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.user.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.tree.user.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.role.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.groups.roles.js');
        $canListUserGroups = $this->modx->hasPermission('usergroup_view') ? 1 : 0;
        $canListRoles = $this->modx->hasPermission('view_role') ? 1 : 0;
        $canListPolicies = $this->modx->hasPermission('policy_view') ? 1 : 0;
        $canListPolicyTemplates = $this->modx->hasPermission('policy_template_view') ? 1 : 0;
        $canEditUser = $this->modx->hasPermission('usergroup_user_edit') ? 1 : 0;
        $canListUser = $this->modx->hasPermission('usergroup_user_list') ? 1 : 0;
        $canAddUserGroup = $this->modx->hasPermission('usergroup_new') ? 1 : 0;
        $canEditUserGroup = $this->modx->hasPermission('usergroup_edit') ? 1 : 0;
        $canDeleteUserGroup = $this->modx->hasPermission('usergroup_delete') ? 1 : 0;
        $this->addHtml('<script>'
                .'MODx.perm.usergroup_view = '.$canListUserGroups.';'
                .'MODx.perm.view_role = '.$canListRoles.';'
                .'MODx.perm.policy_view = '.$canListPolicies.';'
                .'MODx.perm.policy_template_view = '.$canListPolicyTemplates.';'
                .'MODx.perm.usergroup_user_edit = '.$canEditUser.';'
                .'MODx.perm.usergroup_user_list = '.$canListUser.';'
                .'MODx.perm.usergroup_new = '.$canAddUserGroup.';'
                .'MODx.perm.usergroup_edit = '.$canEditUserGroup.';'
                .'MODx.perm.usergroup_delete = '.$canDeleteUserGroup.';'
                .'</script>');
        $this->addHtml("<script>
            Ext.onReady(function() {
                MODx.add('modx-page-groups-roles');
            });</script>");
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/permissions/list.js');
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
        return $this->modx->lexicon('user_group_management');
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
        return array('user','access','policy','context');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Security';
    }
}
