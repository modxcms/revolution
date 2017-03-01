<?php
/**
 * Remove a directory
 *
 * @param string $dir The directory to remove
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class modBrowserFolderRemoveProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('directory_remove');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'name' => false,
            'parent' => '',
        ));
        $dir = $this->getProperty('dir');
        if (empty($dir)) return $this->modx->lexicon('file_folder_err_ns');
        return true;
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        if (!$this->source->checkPolicy('remove')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $dir = $this->getProperty('dir');
        $dir = preg_replace('/(\.+\/)+/', '', htmlspecialchars($dir));
        $success = $this->source->removeContainer($dir);

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
return 'modBrowserFolderRemoveProcessor';
