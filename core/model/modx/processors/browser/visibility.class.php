<?php
/**
 * Set visibility on a directory or file
 *
 * @param string $mode The mode to chmod to
 * @param string $dir The absolute path of the dir
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class modBrowserVisibilityProcessor extends modProcessor {
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
            'visibility' => false,
            'path' => false,
        ));
        if (!$this->getProperty('visibility')) {
            return $this->modx->lexicon('file_folder_visibility_err_ns');
        }
        if (!$this->getProperty('path')) {
            return $this->modx->lexicon('file_folder_err_ns');
        }
        return true;
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();

        $path = $this->getProperty('path');
        $success = $this->source->setVisibility($path, $this->getProperty('visibility'));

        if (!$success || empty($success)) {
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
return 'modBrowserVisibilityProcessor';
