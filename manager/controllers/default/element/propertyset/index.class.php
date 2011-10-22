<?php
/**
 * Load property set management page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementPropertySetIndexManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_propertyset');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.property.set.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/propertyset/index.js');
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
        return $this->modx->lexicon('propertysets');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/propertyset/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('element','category','propertyset');
    }
}