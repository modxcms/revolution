<?php
/**
 * @package modx
 * @subpackage controllers.resource.weblink
 */
class SymlinkUpdateManagerController extends ResourceUpdateManagerController {
    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $managerUrl = $this->context->getOption('manager_url', MODX_MANAGER_URL, $this->modx->_userConfig);
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/util/datetime.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.symlink.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/sections/resource/symlink/update.js');
        $this->modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        MODx.config.publish_document = "'.$this->canPublish.'";
        MODx.onDocFormRender = "'.$this->onDocFormRender.'";
        MODx.ctx = "'.$this->resource->get('context_key').'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-symlink-update"
                ,resource: "'.$this->resource->get('id').'"
                ,record: '.$this->modx->toJSON($this->resourceArray).'
                ,access_permissions: "'.$this->showAccessPermissions.'"
                ,publish_document: "'.$this->canPublish.'"
                ,preview_url: "'.$this->previewUrl.'"
                ,locked: '.($this->locked ? 1 : 0).'
                ,lockedText: "'.$this->lockedText.'"
                ,canSave: '.($this->canSave ? 1 : 0).'
                ,canEdit: "'.($this->modx->hasPermission('edit_document') ? 1 : 0).'"
                ,canCreate: "'.($this->modx->hasPermission('new_document') ? 1 : 0).'"
                ,canDelete: "'.($this->modx->hasPermission('delete_document') ? 1 : 0).'"
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
        return 'resource/symlink/update.tpl';
    }
}