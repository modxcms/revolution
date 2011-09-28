<?php
/**
 * @package modx
 */
/**
 * Loads the Media Sources page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SourceIndexManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('source_view');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/source/modx.panel.sources.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/source/index.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {MODx.load({xtype: "modx-page-sources"});});</script>');
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
        return $this->modx->lexicon('sources');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'source/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('source','namespace');
    }
}