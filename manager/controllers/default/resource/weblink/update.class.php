<?php
/**
 * @package modx
 * @subpackage manager.controllers
 */
class WebLinkUpdateManagerController extends ResourceUpdateManagerController {
    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $managerUrl = $this->context->getOption('manager_url', MODX_MANAGER_URL, $this->modx->_userConfig);
        $this->addJavascript($managerUrl.'assets/modext/util/datetime.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.weblink.js');
        $this->addJavascript($managerUrl.'assets/modext/sections/resource/weblink/update.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        MODx.config.publish_document = "'.$this->canPublish.'";
        MODx.onDocFormRender = "'.$this->onDocFormRender.'";
        MODx.ctx = "'.$this->resource->get('context_key').'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-weblink-update"
                ,resource: "'.$this->resource->get('id').'"
                ,record: '.$this->modx->toJSON($this->resourceArray).'
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
        return 'resource/weblink/update.tpl';
    }

    public function process(array $scriptProperties = array()) {
        $placeholders = parent::process($scriptProperties);
        $this->resourceArray['responseCode'] = $this->resource->getProperty('responseCode','core','HTTP/1.1 301 Moved Permanently');
        return $placeholders;
    }
}