<?php
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
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.tree.user.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.role.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.groups.roles.js');
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
        return 'security/permissions/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','context');
    }
}