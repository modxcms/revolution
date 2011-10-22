<?php
/**
 * @package modx
 */
/**
 * Loads the search page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SearchManagerController extends modManagerController {
    public $searchQuery = '';
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('search');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/util/datetime.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/modx.panel.search.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/search.js');
        $this->addHtml("<script type=\"text/javascript\">Ext.onReady(function() {
    MODx.load({
        xtype: 'modx-page-search'
        ,record: {
            q: '".$this->searchQuery."'
        }
    });
});</script>");
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        if (!empty($this->scriptProperties['q'])) {
            $this->searchQuery = str_replace("'","\'",urldecode($this->scriptProperties['q']));
        }
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('search');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'search/search.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('resource');
    }
}
