<?php

namespace MODX\Processors\Browser\Directory;

use MODX\Processors\Browser\Browser;

/**
 * Renames a file
 *
 * @param string $file The file to rename
 * @param string $newname The new name for the file
 *
 * @package modx
 * @subpackage processors.browser
 */
class Rename extends Browser
{
    public $permission = 'directory_update';
    public $policy = 'save';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $path = $this->sanitize($this->getProperty('path'));
        if (empty($path)) {
            $this->addFieldError('path', $this->modx->lexicon('file_folder_err_ns'));
        }
        $name = $this->sanitize($this->getProperty('name'));
        if (empty($path)) {
            $this->addFieldError('name', $this->modx->lexicon('name_err_ns'));
        }
        if ($this->hasErrors()) {
            return $this->failure();
        }
        $response = $this->source->renameContainer($path, $name);

        return $this->handleResponse($response);
    }
}
