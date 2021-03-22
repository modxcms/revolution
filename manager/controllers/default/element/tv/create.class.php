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
 * Load create tv page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementTVCreateManagerController extends modManagerController {
    public $category;
    public $onTVFormRender = '';
    public $onTVFormPrerender = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('new_tv');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $sources = $this->modx->toJSON($this->getElementSources());
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.tv.template.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.tv.security.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/tv/create.js');
        $this->addHtml('
<script>
// <![CDATA[
MODx.onTVFormRender = "'.$this->onTVFormRender.'";
MODx.perm.unlock_element_properties = "'.($this->modx->hasPermission('unlock_element_properties') ? 1 : 0).'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-tv-create"
        ,record: {
            category: "'.($this->category ? $this->category->get('id') : 0).'"
            ,sources: '.$sources.'
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
    public function process(array $scriptProperties = array()) {
        $placeholders = array();

        /* grab category if preset */
        if (isset($scriptProperties['category'])) {
            $this->category = $this->modx->getObject('modCategory',$scriptProperties['category']);
            if ($this->category != null) {
                $placeholders['category'] = $this->category;
            }
        }

        /* invoke OnTVFormRender event */
        $placeholders['onTVFormRender'] = $this->fireRenderEvent();

        return $placeholders;
    }

    public function getElementSources() {
        $c = $this->modx->newQuery('modContext');
        $c->where(array(
            'key:!=' => 'mgr',
        ));
        $c->sortby($this->modx->escape('rank'),'ASC');
        $c->sortby($this->modx->escape('key'),'DESC');
        $contexts = $this->modx->getCollection('modContext',$c);
        $list = array();
        $this->modx->loadClass('sources.modMediaSource');
        /** @var $source modMediaSource */
        $source = modMediaSource::getDefaultSource($this->modx);
        /** @var modContext $context */
        foreach ($contexts as $context) {
            $list[] = array(
                $context->get('key'),
                $source->get('id'),
                $source->get('name'),
            );
        }
        return $list;
    }

    /**
     * Invoke OnTVFormPrerender event
     * @return void
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onTVFormPrerender = $this->modx->invokeEvent('OnTVFormPrerender',array(
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ));
        if (is_array($this->onTVFormPrerender)) $this->onTVFormPrerender = implode('',$this->onTVFormPrerender);
        $this->setPlaceholder('onTVFormPrerender', $this->onTVFormPrerender);
    }

    /**
     * Invoke OnTVFormRender event
     * @return string
     */
    public function fireRenderEvent() {
        $this->onTVFormRender = $this->modx->invokeEvent('OnTVFormRender',array(
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ));
        if (is_array($this->onTVFormRender)) $this->onTVFormRender = implode('',$this->onTVFormRender);
        $this->onTVFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onTVFormRender);
        return $this->onTVFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('tv_new');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/tv/create.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('tv','category','tv_widget','propertyset','element');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Template+Variables';
    }
}
