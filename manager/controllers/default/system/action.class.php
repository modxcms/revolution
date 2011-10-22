<?php
/**
 * @package modx
 */
/**
 * Loads the System Actions page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemActionManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('actions');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.tree.action.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.tree.menu.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.panel.actions.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/system/action.js');
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
        return $this->modx->lexicon('actions');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'system/action/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('action','menu','namespace');
    }
}