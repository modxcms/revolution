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
 * Load create snippet page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementSnippetCreateManagerController extends modManagerController {
    public $category;
    public $onSnipFormRender = '';
    public $onSnipFormPrerender = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('new_snippet');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.snippet.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/snippet/create.js');
        $this->addHtml('
        <script>
        // <![CDATA[
        MODx.onSnipFormRender = "'.$this->onSnipFormRender.'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-snippet-create"
                ,record: {
                    category: "'.($this->category ? $this->category->get('id') : 0).'"
                }
            });
        });
        // ]]>
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = [])
    {
        $placeholders = [];

        /* grab category if preset */
        if (isset($scriptProperties['category'])) {
            $this->category = $this->modx->getObject(modCategory::class, $scriptProperties['category']);
            if ($this->category !== null) {
                $placeholders['category'] = $this->category;
            }
        }

        /* invoke OnSnipFormRender event */
        $placeholders['onSnipFormRender'] = $this->fireRenderEvent();

        return $placeholders;
    }

    /**
     * Invoke OnSnipFormPrerender event
     * @return void
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onSnipFormPrerender = $this->modx->invokeEvent('OnSnipFormPrerender', [
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ]);
        if (is_array($this->onSnipFormPrerender)) $this->onSnipFormPrerender = implode('',$this->onSnipFormPrerender);
        $this->setPlaceholder('onSnipFormPrerender', $this->onSnipFormPrerender);
    }

    /**
     * Invoke OnSnipFormRender event
     * @return string
     */
    public function fireRenderEvent() {
        $this->onSnipFormRender = $this->modx->invokeEvent('OnSnipFormRender', [
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ]);
        if (is_array($this->onSnipFormRender)) $this->onSnipFormRender = implode('',$this->onSnipFormRender);
        $this->onSnipFormRender = str_replace(['"',"\n","\r"], ['\"','',''],$this->onSnipFormRender);
        return $this->onSnipFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('snippet_new');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/snippet/create.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['snippet','category','system_events','propertyset','element'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Snippets';
    }
}
