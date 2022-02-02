<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\File\modFileHandler;

/**
 * @package modx
 * @subpackage manager.controllers
 */
class StaticResourceUpdateManagerController extends ResourceUpdateManagerController
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
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.static.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/update.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/static/update.js');
        $data = [
            'xtype' => 'modx-page-static-update',
            'resource' => $this->resource->get('id'),
            'record' => $this->resourceArray,
            'publish_document' => $this->canPublish,
            'preview_url' => $this->previewUrl,
            'locked' => (int)$this->locked,
            'lockedText' => $this->lockedText,
            'canSave' => (int)$this->canSave,
            'canEdit' => (int)$this->modx->hasPermission('edit_document'),
            'canCreate' => (int)$this->modx->hasPermission('new_document'),
            'canDelete' => (int)$this->modx->hasPermission('delete_document'),
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
     * Used to set values on the resource record sent to the template for derivative classes
     *
     * @return void|string
     */
    public function prepareResource()
    {
        $wctx = $this->resource->get('context_key');
        if (!empty($wctx)) {
            $workingContext = $this->modx->getContext($wctx);
            if (!$workingContext) {
                $this->failure($this->modx->lexicon('permission_denied'));

                return;
            }
        } else {
            $workingContext =& $this->modx->context;
        }

        /** @var modFileHandler $fileHandler */
        if ($fileHandler = $this->modx->getService('fileHandler', modFileHandler::class, '', ['context' => $workingContext->get('key')])) {
            $baseUrl = $fileHandler->getBaseUrl();
            if (!empty($this->resourceArray['content'])) {
                $this->resourceArray['openTo'] = str_replace($baseUrl, '', dirname($this->resourceArray['content']) . '/');
            } else {
                $this->resourceArray['openTo'] = '/';
            }
        }
    }


    /**
     * Return the location of the template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return 'resource/staticresource/update.tpl';
    }

    public function checkPermissions(): bool
    {
        return $this->modx->hasPermission('edit_document')
            && $this->modx->hasPermission('edit_static_resource');
    }
}
