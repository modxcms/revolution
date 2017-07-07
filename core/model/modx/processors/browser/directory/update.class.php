<?php
/**
 * Renames a directory.
 *
 * @param string $dir The directory to rename
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class modBrowserFolderUpdateProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('directory_update');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }
        $source = $this->getProperty('source',1);

        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $source = modMediaSource::getDefaultSource($this->modx,$source);
        if (!$source->getWorkingContext()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $source->setRequestProperties($this->getProperties());
        $source->initialize();
        if (!$source->checkPolicy('save')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $dir = preg_replace('/[\.]{2,}/', '', htmlspecialchars($this->getProperty('dir')));
        $name = preg_replace('/[\.]{2,}/', '', htmlspecialchars($this->getProperty('name')));
        $success = $source->renameContainer($dir, $name);

        if (!$success) {
            $msg = '';
            $errors = $source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
            return $this->failure($msg);
        }
        return $this->success();
    }

    /**
     * Validate form
     * @return boolean
     */
    public function validate() {
        $dir = $this->getProperty('dir');
        if (empty($dir)) $this->addFieldError('dir',$this->modx->lexicon('file_folder_err_ns'));
        $name = $this->getProperty('name');
        if (empty($name)) $this->addFieldError('name',$this->modx->lexicon('file_folder_err_ns'));

        return !$this->hasErrors();
    }
}
return 'modBrowserFolderUpdateProcessor';
