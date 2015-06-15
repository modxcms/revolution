<?php
/**
 * Loads the Import by HTML page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemImportHtmlManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('import_static');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.tree.resource.simple.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/system/modx.panel.import.html.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/system/import/html.js');
        $this->addHtml("<script>
            Ext.onReady(function() {
                MODx.add('modx-page-import-html');
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
        return $this->modx->lexicon('import_site_html');
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
        return array('import');
    }
}
