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
use MODX\Revolution\modContext;
use MODX\Revolution\modManagerController;
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\Sources\modMediaSource;
use MODX\Revolution\Sources\modMediaSourceElement;

/**
 * Load create template page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementTVUpdateManagerController extends modManagerController {
    /** @var modCategory $category */
    public $category;
    /** @var modTemplateVar $tv */
    public $tv;
    /** @var array $tvArray */
    public $tvArray = [];
    /** @var string $onTVFormRender */
    public $onTVFormRender = '';
    /** @var string $onTVFormPrerender */
    public $onTVFormPrerender = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_tv');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.tv.template.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.tv.security.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/tv/update.js');
        $this->addHtml('
        <script>
        // <![CDATA[
        MODx.onTVFormRender = "'.$this->onTVFormRender.'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-tv-update"
                ,id: "'.$this->tvArray['id'].'"
                ,record: '.$this->modx->toJSON($this->tvArray).'
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
    public function process(array $scriptProperties = []) {
        $placeholders = [];

        /* load tv */
        if (empty($scriptProperties['id']) || strlen($scriptProperties['id']) !== strlen((integer)$scriptProperties['id'])) {
            return $this->failure($this->modx->lexicon('tv_err_ns'));
        }
        $this->tv = $this->modx->getObject(modTemplateVar::class, ['id' => $scriptProperties['id']]);
        if ($this->tv == null) return $this->failure($this->modx->lexicon('tv_err_nf'));
        if (!$this->tv->checkPolicy('view')) return $this->failure($this->modx->lexicon('access_denied'));

        /* get properties */
        $properties = $this->tv->get('properties');
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
        $this->tvArray = $this->tv->toArray();
        $this->tvArray['properties'] = $data;
        $this->tvArray['default_text'] = $this->tv->getContent();

        $this->tvArray['sources'] = $this->getElementSources();

        $this->prepareElement();

        /* load tv into parser */
        $placeholders['tv'] = $this->tv;

        /* invoke OnTVFormRender event */
        $placeholders['onTVFormRender'] = $this->fireRenderEvent();

        return $placeholders;
    }

    /**
     * Prepare the element and get the static openTo path if needed
     *
     * @return void|string
     */
    public function prepareElement() {
        $this->tvArray['openTo'] = '/';
        if (!empty($this->tvArray['static'])) {
            $file = $this->tv->get('static_file');
            $this->tvArray['openTo'] = dirname($file).'/';
        }
        return $this->tvArray['openTo'];
    }


    public function getElementSources()
    {
        $c = $this->modx->newQuery(modContext::class);
        $c->leftJoin(modMediaSourceElement::class, 'SourceElements', [
            'SourceElements.object' => $this->tv->get('id'),
            'SourceElements.object_class' => $this->tv->_class,
            'SourceElements.context_key = modContext.key',
        ]);
        $c->leftJoin(modMediaSource::class, 'Source', 'SourceElements.source = Source.id');
        $c->select($this->modx->getSelectColumns(modContext::class, 'modContext'));
        $c->select($this->modx->getSelectColumns(modMediaSourceElement::class, 'SourceElements'));
        $c->select($this->modx->getSelectColumns(modMediaSource::class, 'Source', '', ['name']));
        $c->where(['key:!=' => 'mgr']);
        $c->sortby($this->modx->escape('rank'));
        $c->sortby($this->modx->escape('key'), 'DESC');
        $contexts = $this->modx->getCollection(modContext::class, $c);
        $list = [];
        /** @var modContext $context */
        foreach ($contexts as $context) {
            $source = $context->get('source');
            $list[] = [
                $context->get('key'),
                !empty($source) ? $source : $this->modx->getOption('default_media_source', null, 1),
                $context->get('name'),
            ];
        }
        return $list;
    }

    /**
     * Invoke OnTVFormPrerender event
     * @return void
     */
    public function firePreRenderEvents()
    {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML into the panel */
        $this->onTVFormPrerender = $this->modx->invokeEvent('OnTVFormPrerender', [
            'id' => $this->tvArray['id'],
            'tv' => &$this->tv,
            'mode' => modSystemEvent::MODE_UPD,
        ]);
        if (is_array($this->onTVFormPrerender)) {
            $this->onTVFormPrerender = implode('', $this->onTVFormPrerender);
        }
        $this->setPlaceholder('onTVFormPrerender', $this->onTVFormPrerender);
    }

    /**
     * Invoke OnTVFormRender event
     * @return string
     */
    public function fireRenderEvent() {
        $this->onTVFormRender = $this->modx->invokeEvent('OnTVFormRender', [
            'id' => $this->tvArray['id'],
            'tv' => &$this->tv,
            'mode' => modSystemEvent::MODE_UPD,
        ]);
        if (is_array($this->onTVFormRender)) $this->onTVFormRender = implode('',$this->onTVFormRender);
        $this->onTVFormRender = str_replace(['"',"\n","\r"], ['\"','',''],$this->onTVFormRender);
        return $this->onTVFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('tv').': '.$this->tvArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/tv/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['tv','category','tv_widget','propertyset','element'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Template+Variables';
    }
}
