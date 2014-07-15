<?php
/**
 * Load update template page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementTemplateUpdateManagerController extends modManagerController {
    /** @var modCategory $category */
    public $category;
    /** @var modTemplate $template */
    public $template;
    /** @var array $templateArray */
    public $templateArray;
    /** @var string $onTempFormRender */
    public $onTempFormRender = '';
    /** @var string $onTempFormPrerender */
    public $onTempFormPrerender = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_template');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.template.tv.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.template.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/template/update.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-template-update"
                ,id: "'.$this->templateArray['id'].'"
                ,record: '.$this->modx->toJSON($this->templateArray).'
            });
        });
        MODx.onTempFormRender = "'.$this->onTempFormRender.'";
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

        /* load template */
        if (empty($scriptProperties['id']) || strlen($scriptProperties['id']) !== strlen((integer)$scriptProperties['id'])) {
            return $this->failure($this->modx->lexicon('template_err_ns'));
        }
        $this->template = $this->modx->getObject('modTemplate', array('id' => $scriptProperties['id']));
        if ($this->template == null) return $this->failure($this->modx->lexicon('template_err_nf'));
        if (!$this->template->checkPolicy('view')) return $this->failure($this->modx->lexicon('access_denied'));

        /* get properties */
        $properties = $this->template->get('properties');
        if (!is_array($properties)) $properties = array();

        $data = array();
        foreach ($properties as $property) {
            $data[] = array(
                $property['name'],
                $property['desc'],
                !empty($property['type']) ? $property['type'] : 'textfield',
                !empty($property['options']) ? $property['options'] : array(),
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                false, /* overridden set to false */
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
                !empty($property['area_trans']) ? $property['area_trans'] : '',
            );
        }
        $this->templateArray = $this->template->toArray();
        $this->templateArray['properties'] = $data;
        $this->templateArray['content'] = $this->template->getContent();

        $this->prepareElement();

        /* load template into parser */
        $placeholders['template'] = $this->template;

        /* invoke OnTempFormRender event */
        $placeholders['onTempFormRender'] = $this->fireRenderEvent();

        return $placeholders;
    }

    /**
     * Prepare the element and get the static openTo path if needed
     *
     * @return void|string
     */
    public function prepareElement() {
        $this->templateArray['openTo'] = '/';
        if (!empty($this->templateArray['static'])) {
            $file = $this->template->get('static_file');
            $this->templateArray['openTo'] = dirname($file).'/';
        }
        return $this->templateArray['openTo'];
    }

    /**
     * Invoke OnTempFormPrerender event
     * @return void
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onTempFormPrerender = $this->modx->invokeEvent('OnTempFormPrerender',array(
            'id' => $this->templateArray['id'],
            'template' => &$this->template,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($this->onTempFormPrerender)) $this->onTempFormPrerender = implode('',$this->onTempFormPrerender);
        $this->setPlaceholder('onTempFormPrerender', $this->onTempFormPrerender);
    }

    /**
     * Invoke OnTempFormRender event
     * @return string
     */
    public function fireRenderEvent() {
        $this->onTempFormRender = $this->modx->invokeEvent('OnTempFormRender',array(
            'id' => $this->templateArray['id'],
            'template' => &$this->template,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($this->onTempFormRender)) $this->onTempFormRender = implode('',$this->onTempFormRender);
        $this->onTempFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onTempFormRender);
        return $this->onTempFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('template').': '.$this->templateArray['templatename'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/template/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('template','category','system_events','propertyset','element','tv');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Templates';
    }
}
