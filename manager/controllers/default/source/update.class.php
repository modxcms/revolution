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
class SourceUpdateManagerController extends modManagerController {
    /** @var modMediaSource $source */
    public $source;
    public $sourceArray = array();
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('sources');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/source/modx.grid.source.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/source/modx.panel.source.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/source/update.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {MODx.load({
    xtype: "modx-page-source-update"
    ,record: '.$this->modx->toJSON($this->sourceArray).'
});});</script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        if (empty($this->scriptProperties['id'])) return $this->failure($this->modx->lexicon('source_err_ns'));
        $this->source = $this->modx->getObject('sources.modMediaSource',$this->scriptProperties['id']);
        if (empty($this->source)) return $this->failure($this->modx->lexicon('source_err_nf'));

        $properties = $this->source->getProperties();
        $data = array();
        foreach ($properties as $property) {
            $data[] = array(
                $property['name'],
                $property['desc'],
                $property['type'],
                $property['options'],
                $property['value'],
                $property['lexicon'],
                $property['overridden'],
                $property['desc_trans'],
                $property['name_trans'],
            );
        }
        $this->sourceArray = $this->source->toArray();
        $this->sourceArray['properties'] = $data;

        return array();
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('source_update');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'source/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('source','namespace','propertyset');
    }
}