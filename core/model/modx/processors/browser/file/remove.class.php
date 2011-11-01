<?php
/**
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class modBrowserFileRemoveProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_remove');
    }
    public function getLanguageTopics() {
        return array('file');
    }

    public function process() {
        if (empty($scriptProperties['file'])) return $this->modx->error->failure($this->modx->lexicon('file_err_ns'));
        $source = $this->modx->getOption('source',$scriptProperties,1);

        $loaded = $this->getSource();
        if (!($this->source instanceof modMediaSource)) {
            return $loaded;
        }
        
        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $source = modMediaSource::getDefaultSource($this->modx,$source);
        if (!$source->getWorkingContext()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $source->setRequestProperties($scriptProperties);
        $source->initialize();
        $success = $source->removeObject($scriptProperties['file']);

        if (empty($success)) {
            $msg = '';
            $errors = $source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
            return $this->failure();
        }
        return $this->success();
    }

    /**
     * @return boolean|string
     */
    public function getSource() {
        $source = $this->getProperty('source',1);
        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx,$source);
        if (!$this->source->getWorkingContext()) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->source->setRequestProperties($this->getProperties());
        return $this->source->initialize();
    }
}
return 'modBrowserFileRemoveProcessor';