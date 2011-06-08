<?php
/**
 * Load property set management page
 *
 * @package modx
 * @subpackage manager.element.propertyset
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
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/element/modx.panel.property.set.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/sections/element/propertyset/index.js');
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
        return $this->modx->lexicon('property_sets');
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