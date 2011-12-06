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


        $source = $this->getSource();
        if ($source !== true) {
            return $source;
        }
        
        $this->source = $this->getProperty('source',1);
        $this->modx->setPlaceholder('mediasource.res_id',$this->getProperty('res_id'));          
        $fileArray = $this->source->getObjectContents($file);

        if (empty($fileArray)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
            return $this->failure($msg);
        }
        return $this->success('',$fileArray);
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
return 'modBrowserFileGetProcessor';