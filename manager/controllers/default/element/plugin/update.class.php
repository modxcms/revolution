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
use MODX\Revolution\modPlugin;
use MODX\Revolution\modSystemEvent;

/**
 * Load update plugin page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementPluginUpdateManagerController extends modManagerController {
    /** @var modCategory $category */
    public $category;
    /** @var modPlugin $plugin */
    public $plugin;
    /** @var array $pluginArray */
    public $pluginArray;
    /** @var string $onPluginFormRender */
    public $onPluginFormRender = '';
    /** @var string $onPluginFormPrerender */
    public $onPluginFormPrerender = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_plugin');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.plugin.event.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.plugin.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/plugin/update.js');
        $this->addHtml('
        <script>
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-plugin-update"
                ,id: "'.$this->pluginArray['id'].'"
                ,record: '.$this->modx->toJSON($this->pluginArray).'
            });
        });
        MODx.onPluginFormRender = "'.$this->onPluginFormRender.'";
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

        /* load plugin */
        if (empty($scriptProperties['id']) || strlen($scriptProperties['id']) !== strlen((integer)$scriptProperties['id'])) {
            return $this->failure($this->modx->lexicon('plugin_err_ns'));
        }
        $this->plugin = $this->modx->getObject(modPlugin::class, ['id' => $scriptProperties['id']]);
        if ($this->plugin == null) return $this->failure($this->modx->lexicon('plugin_err_nf'));
        if (!$this->plugin->checkPolicy('view')) return $this->failure($this->modx->lexicon('access_denied'));

        /* get properties */
        $properties = $this->plugin->get('properties');
        if (!is_array($properties)) $properties = [];

        $data = [];
        foreach ($properties as $property) {
            $data[] = [
                $property['name'],
                $property['desc'],
                !empty($property['type']) ? $property['type'] : 'textfield',
                !empty($property['options']) ? $property['options'] : [],
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                false, /* overridden set to false */
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
                !empty($property['area_trans']) ? $property['area_trans'] : '',
            ];
        }
        $this->pluginArray = $this->plugin->toArray();
        $this->pluginArray['properties'] = $data;
        $this->pluginArray['plugincode'] = $this->plugin->getContent();
        if (strpos(ltrim($this->pluginArray['plugincode']),'<?php') !== 0) {
            $this->pluginArray['plugincode'] = "<?php\n".$this->pluginArray['plugincode'];
        }

        $this->prepareElement();

        /* load plugin into parser */
        $placeholders['plugin'] = $this->plugin;

        /* invoke OnPluginFormRender event */
        $placeholders['onPluginFormRender'] = $this->fireRenderEvent();

        return $placeholders;
    }

    /**
     * Prepare the element and get the static openTo path if needed
     *
     * @return void|string
     */
    public function prepareElement() {
        $this->pluginArray['openTo'] = '/';
        if (!empty($this->pluginArray['static'])) {
            $file = $this->plugin->get('static_file');
            $this->pluginArray['openTo'] = dirname($file).'/';
        }
        return $this->pluginArray['openTo'];
    }

    /**
     * Invoke OnPluginFormPrerender event
     * @return string
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onPluginFormPrerender = $this->modx->invokeEvent('OnPluginFormPrerender', [
            'id' => $this->pluginArray['id'],
            'plugin' => &$this->plugin,
            'mode' => modSystemEvent::MODE_UPD,
        ]);
        if (is_array($this->onPluginFormPrerender)) $this->onPluginFormPrerender = implode('',$this->onPluginFormPrerender);
        $this->setPlaceholder('onPluginFormPrerender', $this->onPluginFormPrerender);
    }

    /**
     * Invoke OnPluginFormRender event
     * @return string
     */
    public function fireRenderEvent() {
        $this->onPluginFormRender = $this->modx->invokeEvent('OnPluginFormRender', [
            'id' => $this->pluginArray['id'],
            'plugin' => &$this->plugin,
            'mode' => modSystemEvent::MODE_UPD,
        ]);
        if (is_array($this->onPluginFormRender)) $this->onPluginFormRender = implode('',$this->onPluginFormRender);
        $this->onPluginFormRender = str_replace(['"',"\n","\r"], ['\"','',''],$this->onPluginFormRender);
        return $this->onPluginFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('plugin').': '.$this->pluginArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/plugin/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['plugin','category','system_events','propertyset','element'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Plugins';
    }
}
