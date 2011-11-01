<?php
/**
 * Updates a file.
 *
 * @param string $file The absolute path of the file
 * @param string $name Will rename the file if different
 * @param string $content The new content of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class modBrowserFileUpdateProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_update');
    }
    public function getLanguageTopics() {
        return array('file');
    }
    public function process() {
        /* get base paths and sanitize incoming paths */
        $filePath = rawurldecode($this->getProperty('file',''));

        $source = $this->getSource();
        if ($source !== true) {
            return $source;
        }

        $path = $this->source->updateObject($filePath,$this->getProperty('content'));
        if (empty($path)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
            return $this->failure($msg);
        }
        return $this->success('',array(
            'file' => $path,
        ));
    }

    /**
     * @return boolean|string
     */
    public function getSource() {
        $source = $this->getProperty('source',1);
        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $source = modMediaSource::getDefaultSource($this->modx,$source);
        if (!$source->getWorkingContext()) {
            return $this->modx->lexicon('permission_denied');
        }
        $source->setRequestProperties($this->getProperties());
        return $source->initialize();
    }
}
return 'modBrowserFileUpdateProcessor';