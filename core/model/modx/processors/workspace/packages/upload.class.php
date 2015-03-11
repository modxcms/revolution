<?php
/**
 * Upload transport package to Packages directory
 *
 * @param string $file The transport package to upload
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class modBrowserPackageUploadProcessor extends modProcessor {
    /** @var modMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_upload');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        if(empty($_FILES)){
            return $this->modx->lexicon('no_file_err');
        }
        $this->getSource();
        $this->setProperty('files',$_FILES);
        return true;
    }

    public function process() {

        // Even though we're not using media sources, it seems like the
        // easiest way to check permissions (and waste time/effort/memory)
        $this->source->initialize();
        if (!$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        // Prepare the upload path and check it exists
        $destination = $this->modx->getOption('core_path').'packages/';
        if (!is_dir($destination)) {
            return $this->failure("Packages directory doesnt appear to exist!"); //@TODO Lexiconize
        }

        // Grab the file
        $file =  array_shift($this->getProperty('files'));

        // Check MIME type of file
        if (!in_array(strtolower($file['type']), array('application/zip', 'application/x-zip-compressed', 'application/x-zip', 'application/octet-stream'))) {
            return $this->failure("1 This file does not appear to be a transport package"); //@TODO Lexiconize
        }

        // Check valid name of file
        if (!preg_match("/.+\\.transport\\.zip$/i",$file['name'])) {
            return $this->failure("2 This file [{$file['name']}] does not appear to be a transport package"); //@TODO Lexiconize
        }

        // Return response
        if (move_uploaded_file($file['tmp_name'],$destination.$file['name'])) {
            return $this->success();
        } else {
            return $this->failure($this->modx->lexicon('unknown_error'));
        }

    }

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
    public function getSource() {
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx);
        if (empty($this->source) || !$this->source->getWorkingContext()) {
            return false;
        }
        return $this->source;
    }
}
return 'modBrowserPackageUploadProcessor';
