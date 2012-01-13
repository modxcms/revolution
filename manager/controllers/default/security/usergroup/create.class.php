<?php
/**
 * Loads the usergroup create page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityUserGroupCreateManagerController extends modManagerController {
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
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.context.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.category.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.source.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.user.group.js');
        $canEditUsers = $this->modx->hasPermission('usergroup_user_edit') ? 1 : 0;
        $canListUsers = $this->modx->hasPermission('usergroup_user_list') ? 1 : 0;
        $this->addHtml('<script type="text/javascript">MODx.perm.usergroup_user_edit = '.$canEditUsers.';MODx.perm.usergroup_user_list = '.$canListUsers.';</script>');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/usergroup/create.js');
        $this->addHtml('<script type="text/javascript">
        MODx.perm.usergroup_user_edit = '.$canEditUsers.';
        MODx.perm.usergroup_user_list = '.$canListUsers.';
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-user-group-create"
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
    public function process(array $scriptProperties = array()) {}

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('user_group_new');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/usergroup/create.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','context');
    }
}
