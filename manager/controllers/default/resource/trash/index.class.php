<?php
class ResourceTrashIndexManagerController extends \modExtraManagerController {

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.trash.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/trash/index.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() { MODx.add("modx-page-trash"); });</script>');
    }

    /**
     * @return null|string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('trash.page_title');
    }

    /**
     * @return string
     */
    public function getTemplateFile() {
        return ''; //'resource/trash/index.tpl';
    }


    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('source_view');
    }


    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('trash','namespace');
    }

    /**
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = []) {
        $this->modx->lexicon->load('core:trash');
    }
}