<?php
/**
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.system.file
 */
class SystemFileEditManagerController extends modManagerController {
    /** @var string The basename of the file */
    public $filename = '';
    /** @var array An array of data about the file */
    public $fileRecord = array();
    /** @var bool A boolean stating whether or not this file can be saved */
    public $canSave = false;
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('file_view');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->modx->regClientStartupScript($this->modx->getOption('manager_url').'assets/modext/sections/system/file/edit.js');
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-file-edit"
                ,file: "'.$this->filename.'"
                ,record: '.$this->modx->toJSON($this->fileRecord).'
                ,canSave: '.($this->canSave ? 1 : 0).'
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
        
        if (empty($_GET['file'])) return $this->failure($this->modx->lexicon('file_err_nf'));
        $this->loadWorkingContext();

        /* format filename */
        $this->filename = preg_replace('#([\\\\]+|/{2,})#', '/',$scriptProperties['file']);
        $this->modx->getService('fileHandler', 'modFileHandler', '',array('context' => $this->workingContext->key));
        $root = $this->modx->fileHandler->getBasePath(false);
        if ($this->workingContext->getOption('filemanager_path_relative',true)) {
            $root = $this->workingContext->getOption('base_path','').$root;
        }
        $file = $this->modx->fileHandler->make($root.$this->filename);

        if (!$file->exists()) return $this->failure($this->modx->lexicon('file_err_nf'));
        if (!$file->isReadable()) {
            return $this->failure($this->modx->lexicon('file_err_perms'));
        }
        $imagesExts = array('jpg','jpeg','png','gif','ico');
        $fileExtension = pathinfo($this->filename,PATHINFO_EXTENSION);

        $this->fileRecord = array(
            'name' => $this->filename,
            'size' => $file->getSize(),
            'last_accessed' => $file->getLastAccessed(),
            'last_modified' => $file->getLastModified(),
            'content' => $file->getContents(),
            'image' => in_array($fileExtension,$imagesExts) ? true : false,
        );
        $this->canSave = $file->isWritable() ? true : false;

        $placeholders['fa'] = $this->fileRecord;
        $placeholders['OnFileEditFormPrerender'] = $this->fireEvents();

        return $placeholders;
    }

    /**
     * Invoke OnFileEditFormPrerender event
     * @return string
     */
    public function fireEvents() {
        $onFileEditFormPrerender = $this->modx->invokeEvent('OnFileEditFormPrerender',array(
            'mode' => modSystemEvent::MODE_UPD,
            'file' => $this->filename,
            'fa' => &$this->fileRecord,
        ));
        if (is_array($onFileEditFormPrerender)) $onFileEditFormPrerender = implode('',$onFileEditFormPrerender);
        return $onFileEditFormPrerender;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('file_edit').': '.basename($this->filename);
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'system/file/edit.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('file');
    }
}