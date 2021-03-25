<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
class WebLinkCreateManagerController extends ResourceCreateManagerController {
    public function checkPermissions() {
        return $this->modx->hasPermission('new_document') && $this->modx->hasPermission('new_weblink');
    }

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
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.weblink.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/create.js');
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
        return 'resource/weblink/create.tpl';
    }

    public function process(array $scriptProperties = array()) {
        $placeholders = parent::process($scriptProperties);
        $this->resourceArray['responseCode'] = $this->resource->getProperty('responseCode','core',$_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
        return $placeholders;
    }
}
