<?php
/**
 * Renames a file
 *
 * @param string $file The file to rename
 * @param string $newname The new name for the file
 *
 * @package modx
 * @subpackage processors.browser
 */
class modBrowserFolderRenameProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('directory_update');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'name' => false,
            'parent' => '',
        ));
        $path = $this->getProperty('path');
        if (empty($path)) return $this->modx->lexicon('file_folder_err_ns');
        return true;
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        if (!$this->source->checkPolicy('save')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $fields = $this->getProperties();
        if (!$this->validate($fields)) {
            return $this->failure();
        }

        $path = $this->getProperty('path');
        $path = preg_replace('/[\.]{2,}/', '', htmlspecialchars($path));
        $name = $this->getProperty('name');
        $name = preg_replace('/[\.]{2,}/', '', htmlspecialchars($name));
        $response = $this->source->renameContainer($path, $name);
        return $this->handleResponse($response);
    }

    /**
     * Validate the fields passed in
     *
     * @param array $fields
     * @return boolean
     */
    public function validate(array $fields) {
        if (empty($fields['path'])) {
            $this->addFieldError('path',$this->modx->lexicon('path_err_ns'));
        }
        if (empty($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('name_err_ns'));
        }

        return !$this->hasErrors();
    }

    /**
     * Handle the response from the source
     * @param string $response
     * @return array|string
     */
    public function handleResponse($response) {
        if (empty($response)) {
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
return 'modBrowserFolderRenameProcessor';
