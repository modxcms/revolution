<?php
/**
 * Load create plugin page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementPluginCreateManagerController extends modManagerController {
    public $category;
    public $onPluginFormRender = '';
    public $onPluginFormPrerender = '';
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('new_plugin');
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
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/plugin/create.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-plugin-create"
                ,record: {
                    category: "'.($this->category ? $this->category->get('id') : 0).'"
                }
            });
        });
        MODx.onPluginFormRender = "'.$this->onPluginFormRender.'";
        MODx.perm.unlock_element_properties = "'.($this->modx->hasPermission('unlock_element_properties') ? 1 : 0).'";
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

        /* invoke OnPluginFormRender event */
        $placeholders['onPluginFormRender'] = $this->fireRenderEvent();

        return $placeholders;
    }

    /**
     * Invoke OnPluginFormPrerender event
     * @return void
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onPluginFormPrerender = $this->modx->invokeEvent('OnPluginFormPrerender',array(
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ));
        if (is_array($this->onPluginFormPrerender)) $this->onPluginFormPrerender = implode('',$this->onPluginFormPrerender);
        $this->setPlaceholder('onPluginFormPrerender', $this->onPluginFormPrerender);
    }

    /**
     * Invoke OnPluginFormRender event
     * @return string
     */
    public function fireRenderEvent() {
        $this->onPluginFormRender = $this->modx->invokeEvent('OnPluginFormRender',array(
            'id' => 0,
            'mode' => modSystemEvent::MODE_NEW,
        ));
        if (is_array($this->onPluginFormRender)) $this->onPluginFormRender = implode('',$this->onPluginFormRender);
        $this->onPluginFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onPluginFormRender);
        return $this->onPluginFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('plugin_new');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/plugin/create.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('plugin','category','system_events','propertyset','element');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Plugins';
    }
}