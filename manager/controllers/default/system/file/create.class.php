<?php
/**
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemFileCreateManagerController extends modManagerController {
    /** @var string The directory to create in */
    public $directory = '';
    /** @var modMediaSource $source */
    public $source = null;

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('file_create');
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('file');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/file/create.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-file-create"
                ,record: {
                    directory: "'.$this->directory.'",
                    source: "'.$this->source->get('id')  .'"
                }
            });
        });</script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        $this->modx->lexicon->load('file');
        $this->getSource();

        $directory = !empty($scriptProperties['directory']) ? $scriptProperties['directory'] : '';
        $this->directory = ltrim(strip_tags(str_replace(array('../','./'),'',$directory)),'/');
        $this->directory = htmlspecialchars(strip_tags($this->directory));
        
        $this->loadWorkingContext();

        $placeholders['OnFileCreateFormPrerender'] = $this->fireEvents();

        return $placeholders;
    }

    /**
     * Get the active source
     * @return modMediaSource
     */
    public function getSource() {
        /** @var modMediaSource|modFileMediaSource $source */
        if (!$this->source) {
            $this->modx->loadClass('sources.modMediaSource');
            $source = $this->modx->getOption('source',$this->scriptProperties,false);
            if (!empty($source)) {
                $source = $this->modx->getObject('source.modMediaSource',$source);
            }
            if (empty($source)) {
                $source = modMediaSource::getDefaultSource($this->modx);
            }
            if (!$source->getWorkingContext()) {
                return $this->failure($this->modx->lexicon('permission_denied'));
            }
            $source->setRequestProperties($this->scriptProperties);
            $source->initialize();
            $this->source = $source;
        }
        return $this->source;
    }

    /**
     * Invoke OnFileEditFormPrerender event
     * @return string
     */
    public function fireEvents() {
        $OnFileCreateFormPrerender = $this->modx->invokeEvent('OnFileCreateFormPrerender',array(
            'mode' => modSystemEvent::MODE_NEW,
            'directory' => $this->directory,
        ));
        if (is_array($OnFileCreateFormPrerender)) $OnFileCreateFormPrerender = implode('',$OnFileCreateFormPrerender);
        return $OnFileCreateFormPrerender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('file_create');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }
}
