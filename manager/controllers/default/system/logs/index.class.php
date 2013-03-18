<?php
/**
 * Loads the manager logs page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemLogsIndexManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('logs');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.manager.log.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/logs.js');
        $this->addHtml("<script>
            Ext.onReady(function() {
                MODx.load({ xtype: 'modx-page-manager-log' });
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
        return $this->modx->lexicon('manager_log');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'system/logs/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('manager_log');
    }
}