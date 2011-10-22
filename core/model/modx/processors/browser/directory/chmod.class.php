<?php
/**
 * Chmod a directory
 *
 * @param string $mode The mode to chmod to
 * @param string $dir The absolute path of the dir
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class modBrowserFolderChmodProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('directory_chmod');
    }

    public function getLanguageTopics() {
        return array('file');
    }
    
    public function initialize() {
        $this->setDefaultProperties(array(
            'mode' => false,
            'dir' => false,
        ));
        if (!$this->getProperty('mode')) return $this->modx->lexicon('file_err_chmod_ns');
        if (!$this->getProperty('dir')) return $this->modx->lexicon('file_folder_err_ns');
        return true;
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();

        $success = $this->source->chmodContainer($this->getProperty('dir'),$this->getProperty('mode'));

        if (empty($success)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->modx->error->addField($k,$msg);
            }
            return $this->failure($msg);
        }
        return $this->success();
    }

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
    public function getSource() {
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx,$this->getProperty('source'));
        if (empty($this->source) || !$this->source->getWorkingContext()) {
            return false;
        }
        return $this->source;
    }
}
return 'modBrowserFolderChmodProcessor';