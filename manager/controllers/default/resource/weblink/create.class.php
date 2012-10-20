<?php
/**
 * @package modx
 * @subpackage manager.controllers
 */
class WebLinkCreateManagerController extends ResourceCreateManagerController {
    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/util/datetime.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.weblink.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/weblink/create.js');
        $this->addHtml('<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$this->canPublish.'";
MODx.onDocFormRender = "'.$this->onDocFormRender.'";
MODx.ctx = "'.$this->ctx.'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-weblink-create"
        ,record: '.$this->modx->toJSON($this->resourceArray).'
        ,publish_document: "'.$this->canPublish.'"
        ,canSave: "'.($this->modx->hasPermission('save_document') ? 1 : 0).'"
        ,show_tvs: '.(!empty($this->tvCounts) ? 1 : 0).'
    });
});
// ]]>
</script>');
    }
    
    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'resource/weblink/create.tpl';
    }

    public function process(array $scriptProperties = array()) {
        $placeholders = parent::process($scriptProperties);
        $this->resourceArray['responseCode'] = $this->resource->getProperty('responseCode','core','HTTP/1.1 301 Moved Permanently');
        return $placeholders;
    }
}