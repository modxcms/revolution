<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modCategory;
use MODX\Revolution\modManagerController;
use MODX\Revolution\modSystemEvent;

/**
 * Load create chunk page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementChunkCreateManagerController extends modManagerController {
    public $category;
    public $onChunkFormRender;
    public $onChunkFormPrerender;
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('new_chunk');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.chunk.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/chunk/create.js');

        $this->addHtml('
        <script>
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-chunk-create"
                ,record: {
                    category: "'.($this->category ? $this->category->get('id') : 0).'"
                }
            });
        });
        MODx.onChunkFormRender = "'.$this->onChunkFormRender.'";
        // ]]>
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
        $placeholders = [];

        $placeholders['category'] = $this->getCategory($scriptProperties);

        /* invoke OnChunkFormRender event */
        $placeholders['onChunkFormRender'] = $this->fireRenderEvent();
        $placeholders['onRTEInit'] = $this->loadRte();

        return $placeholders;
    }

    /**
     * Get the current category
     * @param array $scriptProperties
     * @return \xPDO\Om\xPDOObject
     */
    public function getCategory(array $scriptProperties = [])
    {
        /* grab default category if specified */
        if (isset($scriptProperties['category'])) {
            $this->category = $this->modx->getObject(modCategory::class, $scriptProperties['category']);
        } else {
            $this->category = null;
        }
        return $this->category;
    }

    /**
     * Invoke OnRichTextEditorInit event, loading the RTE
     * @return string
     */
    public function loadRte()
    {
        $o = '';
        if ($this->modx->getOption('use_editor') === 1) {
            $onRTEInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
                'elements' => ['post'],
                'mode' => modSystemEvent::MODE_NEW,
            ]);
            if (is_array($onRTEInit)) {
                $onRTEInit = implode('', $onRTEInit);
            }
            $o = $onRTEInit;
        }
        return $o;
    }

    /**
     * Fire the OnChunkFormRender event
     * @return mixed
     */
    public function fireRenderEvent() {
        $this->onChunkFormRender = $this->modx->invokeEvent('OnChunkFormRender', [
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
            'chunk' => null,
        ]);
        if (is_array($this->onChunkFormRender)) $this->onChunkFormRender = implode('', $this->onChunkFormRender);
        $this->onChunkFormRender = str_replace(['"',"\n","\r"], ['\"','',''],$this->onChunkFormRender);
        return $this->onChunkFormRender;
    }

    /**
     * Fire the OnChunkFormPrerender event
     * @return mixed
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onChunkFormPrerender = $this->modx->invokeEvent('OnChunkFormPrerender', [
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
            'chunk' => null,
        ]);
        if (is_array($this->onChunkFormPrerender)) { $this->onChunkFormPrerender = implode('',$this->onChunkFormPrerender); }
        $this->setPlaceholder('onChunkFormPrerender', $this->onChunkFormPrerender);
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('chunk_new');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/chunk/create.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['chunk','category','propertyset','element'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Chunks';
    }
}
