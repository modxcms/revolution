<?php
/**
 * Unpacks archives, currently only zip
 *
 * @package modx
 * @subpackage processors.system.filesys.file
 */
class modUnpackProcessor extends modProcessor {

    public function checkPermissions() {
        return $this->modx->hasPermission('file_unpack');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        $this->properties = $this->getProperties();
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array|string
     */
    public function process() {

        $this->modx->getService('fileHandler', 'modFileHandler');

        $target = $this->properties['path'] . $this->properties['file'];
        $target = preg_replace('/(\.+\/)+/', '', htmlspecialchars($target));
        $fileobj = $this->modx->fileHandler->make($target);

        if (!$this->validate($fileobj)) {
            return $this->failure($this->modx->lexicon('file_err_unzip_invalid_path') . ': ' . $fileobj->getPath());
        }

        // currently the archive content is extracted to the folder where the archive is stored
        if (!$fileobj->unpack($this->properties['path'] . dirname($this->properties['file']))) {
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

        if (empty($this->properties['path'])) {
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

return 'modUnpackProcessor';
