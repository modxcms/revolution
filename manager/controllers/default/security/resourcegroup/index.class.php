<?php
/**
 * Loads the resource group page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityResourceGroupIndexManagerController extends modManagerController {
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
                MODx.load({ xtype: 'modx-page-resource-groups' });
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
        return 'security/resourcegroup/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access');
    }
}