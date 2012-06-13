<?php
/**
 * Load update snippet page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementSnippetUpdateManagerController extends modManagerController {
    /** @var modCategory $category */
    public $category;
    /** @var modSnippet $snippet */
    public $snippet;
    /** @var array $snippetArray */
    public $snippetArray;
    /** @var string $onSnipFormRender */
    public $onSnipFormRender = '';
    /** @var string $onSnipFormPrerender */
    public $onSnipFormPrerender = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_snippet');
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
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/snippet/update.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        MODx.onSnipFormRender = "'.$this->onSnipFormRender.'";
        MODx.perm.unlock_element_properties = "'.($this->modx->hasPermission('unlock_element_properties') ? 1 : 0).'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-snippet-update"
                ,id: "'.$this->snippetArray['id'].'"
                ,record: '.$this->modx->toJSON($this->snippetArray).'
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

        /* load snippet */
        if (empty($scriptProperties['id'])) return $this->failure($this->modx->lexicon('snippet_err_ns'));
        $this->snippet = $this->modx->getObject('modSnippet',$scriptProperties['id']);
        if ($this->snippet == null) return $this->failure($this->modx->lexicon('snippet_err_nf'));
        if (!$this->snippet->checkPolicy('view')) return $this->failure($this->modx->lexicon('access_denied'));

        /* get properties */
        $properties = $this->snippet->get('properties');
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
        $this->snippetArray = $this->snippet->toArray();
        $this->snippetArray['properties'] = $data;
        $this->snippetArray['snippet'] = $this->snippet->getContent();
        if (strpos($this->snippetArray['snippet'],'<?php') === false) {
            $this->snippetArray['snippet'] = "<?php\n".$this->snippetArray['snippet'];
        }

        $this->prepareElement();

        /* load snippet into parser */
        $placeholders['snippet'] = $this->snippet;

        /* invoke OnSnipFormRender event */
        $placeholders['onSnipFormRender'] = $this->fireRenderEvent();

        return $placeholders;
    }

    /**
     * Prepare the element and get the static openTo path if needed
     *
     * @return void|string
     */
    public function prepareElement() {
        $this->snippetArray['openTo'] = '/';
        if (!empty($this->snippetArray['static'])) {
            $file = $this->snippet->get('static_file');
            $this->snippetArray['openTo'] = dirname($file).'/';
        }
        return $this->snippetArray['openTo'];
    }

    /**
     * Invoke OnSnipFormPrerender event
     * @return void
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onSnipFormPrerender = $this->modx->invokeEvent('OnSnipFormPrerender',array(
            'id' => $this->snippetArray['id'],
            'snippet' => &$this->snippet,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($this->onSnipFormPrerender)) $this->onSnipFormPrerender = implode('',$this->onSnipFormPrerender);
        $this->setPlaceholder('onSnipFormPrerender', $this->onSnipFormPrerender);
    }

    /**
     * Invoke OnSnipFormRender event
     * @return string
     */
    public function fireRenderEvent() {
        $this->onSnipFormRender = $this->modx->invokeEvent('OnSnipFormRender',array(
            'id' => $this->snippetArray['id'],
            'snippet' => &$this->snippet,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($this->onSnipFormRender)) $this->onSnipFormRender = implode('',$this->onSnipFormRender);
        $this->onSnipFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onSnipFormRender);
        return $this->onSnipFormRender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('snippet').': '.$this->snippetArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/snippet/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('snippet','category','system_events','propertyset','element');
    }
}
