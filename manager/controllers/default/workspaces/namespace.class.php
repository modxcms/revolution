<?php
/**
 * @package modx
 */
/**
 * Loads Namespace management
 *
 * @package modx
 * @subpackage manager.controllers
 */
class WorkspacesNamespaceManagerController extends modManagerController {

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('namespaces');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/workspace/namespace/modx.namespace.panel.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/workspace/namespace/index.js');
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
        return $this->modx->lexicon('namespaces');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'workspaces/namespace/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('workspace','namespace');
    }
}