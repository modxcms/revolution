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
class modBrowserFileCreateProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_create');
    }
    public function getLanguageTopics() {
        return array('file');
    }
    public function process() {
        /* get base paths and sanitize incoming paths */
        $directory = rawurldecode($this->getProperty('directory',''));
        $directory = ltrim(strip_tags(preg_replace('/[\.]{2,}/', '', htmlspecialchars($directory))),'/');

        $name = $this->getProperty('name');
        $name = ltrim(strip_tags(preg_replace('/[\.]{2,}/', '', htmlspecialchars($name))),'/');

        $loaded = $this->getSource();
        if (!($this->source instanceof modMediaSource)) {
            return $loaded;
        }
        if (!$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $path = $this->source->createObject($directory,$name,$this->getProperty('content'));
        if (empty($path)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
            return $this->failure($msg);
        }
        return $this->success('',array(
            'file' => $directory.ltrim($name,'/'),
        ));
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
return 'modBrowserFileCreateProcessor';
