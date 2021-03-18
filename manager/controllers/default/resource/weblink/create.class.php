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
class WebLinkCreateManagerController extends ResourceCreateManagerController
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
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.weblink.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/create.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/weblink/create.js');
        $data = [
            'xtype' => 'modx-page-weblink-create',
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
        Ext.onReady(function() {MODx.load(' . json_encode($data) . ')});</script>');

        $this->loadRichTextEditor();
    }


    /**
    * Return the location of the template file
    *
    * @return string
    */
    public function getTemplateFile()
    {
        return 'resource/weblink/create.tpl';
    }


    /**
    * @param array $scriptProperties
    *
    * @return mixed
    */
    public function process(array $scriptProperties = [])
    {
        $placeholders = parent::process($scriptProperties);
        $this->resourceArray['responseCode'] =
            $this->resource->getProperty('responseCode', 'core', $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently');

        return $placeholders;
    }
}
