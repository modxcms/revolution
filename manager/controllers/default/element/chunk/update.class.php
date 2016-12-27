<?php
/**
 * Load update chunk page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ElementChunkUpdateManagerController extends modManagerController {
    /** @var string $onChunkFormRender */
    public $onChunkFormRender = '';
    /** @var string $onChunkForPrerender */
    public $onChunkFormPrerender = '';
    /** @var modChunk $chunk */
    public $chunk;
    /** @var array $chunkArray */
    public $chunkArray = array();
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_chunk');
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
        $this->addJavascript($mgrUrl.'assets/modext/sections/element/chunk/update.js');
        $this->addHtml('<script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-chunk-update"
                ,chunk: "'.$this->chunkArray['id'].'"
                ,record: '.$this->modx->toJSON($this->chunkArray).'
            });
        });
        MODx.onChunkFormRender = "'.$this->onChunkFormRender.'";
        MODx.perm.unlock_element_properties = '.($this->modx->hasPermission('unlock_element_properties') ? 1 : 0).';
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
    
        /* grab chunk */
        if (empty($scriptProperties['id']) || strlen($scriptProperties['id']) !== strlen((integer)$scriptProperties['id'])) {
            return $this->failure($this->modx->lexicon('chunk_err_ns'));
        }
        $this->chunk = $this->modx->getObject('modChunk', array('id' => $scriptProperties['id']));
        if (empty($this->chunk)) return $this->failure($this->modx->lexicon('chunk_err_nfs',array('id' => $scriptProperties['id'])));
        if (!$this->chunk->checkPolicy('view')) return $this->failure($this->modx->lexicon('access_denied'));

        if ($this->chunk->get('locked') && !$this->modx->hasPermission('edit_locked')) {
            return $this->failure($this->modx->lexicon('chunk_err_locked'));
        }

        /* grab category for chunk, assign to parser */
        $placeholders['chunk'] = $this->chunk;

        /* invoke OnChunkFormRender event */
        $placeholders['onChunkFormRender'] = $this->fireRenderEvent();

        /* get properties */
        $properties = $this->chunk->get('properties');
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
        $this->chunkArray = $this->chunk->toArray();
        $this->chunkArray['properties'] = $data;
        $this->chunkArray['snippet'] = $this->chunk->getContent();

        $this->prepareElement();

        /* invoke OnRichTextEditorInit event */
        $placeholders['onRTEInit'] = $this->loadRte();

        /* check unlock default element properties permission */
        $placeholders['unlock_element_properties'] = $this->modx->hasPermission('unlock_element_properties') ? 1 : 0;
 
        return $placeholders;
    }

    /**
     * Prepare the element and get the static openTo path if needed
     *
     * @return void|string
     */
    public function prepareElement() {
        $this->chunkArray['openTo'] = '/';
        if (!empty($this->chunkArray['static'])) {
            $file = $this->chunk->get('static_file');
            $this->chunkArray['openTo'] = dirname($file).'/';
        }
        return $this->chunkArray['openTo'];
    }

    /**
     * Fire the OnChunkFormPrerender event
     * @return mixed
     */
    public function firePreRenderEvents() {
        /* PreRender events inject directly into the HTML, as opposed to the JS-based Render event which injects HTML
        into the panel */
        $this->onChunkFormPrerender = $this->modx->invokeEvent('OnChunkFormPrerender',array(
            'id' => $this->chunkArray['id'],
            'mode' => modSystemEvent::MODE_UPD,
            'chunk' => $this->chunk,
        ));
        if (is_array($this->onChunkFormPrerender)) { $this->onChunkFormPrerender = implode('',$this->onChunkFormPrerender); }
        $this->setPlaceholder('onChunkFormPrerender', $this->onChunkFormPrerender);
    }

    /**
     * Invoke OnRichTextEditorInit event, loading the RTE
     * @return string
     */
    public function loadRte() {
        $o = '';
        if ($this->modx->getOption('use_editor') == 1) {
            $onRTEInit = $this->modx->invokeEvent('OnRichTextEditorInit',array(
                'elements' => array('post'),
                'chunk' => &$this->chunk,
                'mode' => modSystemEvent::MODE_UPD,
            ));
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
        $this->onChunkFormRender = $this->modx->invokeEvent('OnChunkFormRender',array(
            'id' => $this->chunk->get('id'),
            'mode' => modSystemEvent::MODE_UPD,
            'chunk' => $this->chunk,
        ));
        if (is_array($this->onChunkFormRender)) $this->onChunkFormRender = implode('', $this->onChunkFormRender);
        $this->onChunkFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onChunkFormRender);
        return $this->onChunkFormRender;
    }


    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('chunk').': '.$this->chunkArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'element/chunk/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('chunk','category','propertyset','element');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Chunks';
    }
}
