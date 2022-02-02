<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage manager.controllers
 */
class StaticResourceCreateManagerController extends ResourceCreateManagerController
{
    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $mgrUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.static.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/create.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/static/create.js');
        $data = [
            'xtype' => 'modx-page-static-create',
            'record' => $this->resourceArray,
            'publish_document' => $this->canPublish,
            'canSave' => (int)$this->modx->hasPermission('save_document'),
            'show_tvs' => (int)!empty($this->tvCounts),
            'mode' => 'create',
        ];
        $this->addHtml('<script>
        MODx.config.publish_document = "' . $this->canPublish . '";
        MODx.onDocFormRender = "' . $this->onDocFormRender . '";
        MODx.ctx = "' . $this->ctx . '";
        Ext.onReady(function() {MODx.load(' . json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE) . ')});</script>');

        $this->loadRichTextEditor();
    }


    /**
     * Return the location of the template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return 'resource/staticresource/create.tpl';
    }

    public function checkPermissions(): bool
    {
        return $this->modx->hasPermission('new_document')
            && $this->modx->hasPermission('new_static_resource');
    }
}
