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
class WebLinkUpdateManagerController extends ResourceUpdateManagerController
{
    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $mgrUrl = $this->context->getOption('manager_url', MODX_MANAGER_URL, $this->modx->_userConfig);
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.weblink.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/update.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/weblink/update.js');
        $data = [
            'xtype' => 'modx-page-weblink-update',
            'resource' => $this->resource->get('id'),
            'record' => $this->resourceArray,
            'publish_document' => $this->canPublish,
            'preview_url' => $this->previewUrl,
            'locked' => (int)$this->locked,
            'lockedText' => $this->lockedText,
            'canSave' => (int)$this->canSave,
            'canEdit' => (int)$this->canEdit,
            'canCreate' => (int)$this->canCreate,
            'canCreateRoot' => (int)$this->canCreateRoot,
            'canDuplicate' => (int)$this->canDuplicate,
            'canDelete' => (int)$this->canDelete,
            'show_tvs' => (int)!empty($this->tvCounts),
        ];
        $this->addHtml('<script>
        MODx.config.publish_document = "' . $this->canPublish . '";
        MODx.onDocFormRender = "' . $this->onDocFormRender . '";
        MODx.ctx = "' . $this->resource->get('context_key') . '";
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
        return 'resource/weblink/update.tpl';
    }


    /**
     * @param array $scriptProperties
     *
     * @return array|mixed
     */
    public function process(array $scriptProperties = [])
    {
        $placeholders = parent::process($scriptProperties);
        $this->resourceArray['responseCode'] =
            $this->resource->getProperty('responseCode', 'core', $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently');

        return $placeholders;
    }

    public function checkPermissions(): bool
    {
        return $this->modx->hasPermission('edit_document')
            && $this->modx->hasPermission('edit_weblink');
    }
}
