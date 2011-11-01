<?php
/**
 * Gets the contents of a file
 *
 * @param string $file The absolute path of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class modBrowserFileGetProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_view');
    }
    public function getLanguageTopics() {
        return array('file');
    }

    public function process() {
        /* format filename */
        $file = rawurldecode($this->getProperty('file',''));
        $source = $this->getProperty('source',1);

        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $source = modMediaSource::getDefaultSource($this->modx,$source);
        if (!$source->getWorkingContext()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $source->setRequestProperties($this->getProperties());
        $source->initialize();
        $fileArray = $source->getObjectContents($file);

        if (empty($fileArray)) {
            $msg = '';
            $errors = $source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
            return $this->failure($msg);
        }
        return $this->success('',$fileArray);

    }
}
return 'modBrowserFileGetProcessor';