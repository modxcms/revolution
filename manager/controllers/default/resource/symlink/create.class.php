<?php
/**
 * @package modx
 * @subpackage manager.controllers
 */
class SymLinkCreateManagerController extends ResourceCreateManagerController {
    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.symlink.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/create.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/symlink/create.js');
        $this->addHtml('<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$this->canPublish.'";
MODx.onDocFormRender = "'.$this->onDocFormRender.'";
MODx.ctx = "'.$this->ctx.'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-symlink-create"
        ,record: '.$this->modx->toJSON($this->resourceArray).'
        ,publish_document: "'.$this->canPublish.'"
        ,canSave: "'.($this->modx->hasPermission('save_document') ? 1 : 0).'"
        ,show_tvs: '.(!empty($this->tvCounts) ? 1 : 0).'
        ,mode: "create"
    });
});
// ]]>
</script>');
        /* load RTE */
        $this->loadRichTextEditor();
    }
    
    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'resource/symlink/create.tpl';
    }
}
