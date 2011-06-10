<?php
/**
 * @package modx
 * @subpackage controllers.resource.symlink
 */
class SymLinkCreateManagerController extends ResourceCreateManagerController {
    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/util/datetime.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.symlink.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/sections/resource/symlink/create.js');
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$this->canPublish.'";
MODx.onDocFormRender = "'.$this->onDocFormRender.'";
MODx.ctx = "'.$this->ctx.'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-symlink-create"
        ,record: '.$this->modx->toJSON($this->resourceArray).'
        ,access_permissions: "'.$this->showAccessPermissions.'"
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
        return 'resource/symlink/create.tpl';
    }
}