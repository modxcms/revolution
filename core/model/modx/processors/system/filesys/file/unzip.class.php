<?php
/**
 * Unpacks a zip archive
 *
 * @package modx
 * @subpackage processors.system.filesys.file
 */
class modUnzipProcessor extends modProcessor {

    public function checkPermissions() {
        return $this->modx->hasPermission('file_unzip');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array|string
     */
    public function process() {

        $this->modx->getService('fileHandler', 'modFileHandler');

        $fileobj = $this->modx->fileHandler->make($this->getProperty('path') . $this->getProperty('file'));

        if (!$this->validate($fileobj)) {
            return $this->failure($this->modx->lexicon('file_err_unzip_invalid_path') . ': ' . $fileobj->getPath());
        }
        
        if (!$fileobj->unpack()) {
            return $this->failure($this->modx->lexicon('file_err_unzip'));
        }

        return $this->success($this->modx->lexicon('file_unzip'));
     }

    /**
     * Validate the incoming fileHandler object
     * @param modFileSystemResource $fileobj
     * @return boolean
     */
    public function validate(modFileSystemResource $fileobj) {

        if (empty($this->getProperty('path'))) {
            $this->addFieldError('path', $this->modx->lexicon('file_folder_err_invalid_path'));
        }

        if (!$fileobj->getParentDirectory()->isWritable()) {
            $this->addFieldError('path', $this->modx->lexicon('files_dirwritable'));
        }

        if (!$fileobj->exists()) {
             $this->addFieldError('path', $this->modx->lexicon('file_err_nf'));
        }
        
        return !$this->hasErrors();
    }
}

return 'modUnzipProcessor';