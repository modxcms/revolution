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
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFolderRenameProcessor extends modBrowserProcessor
{
    public $permission = 'directory_update';
    public $policy = 'save';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $path = rawurldecode($this->getProperty('path'));
        if (empty($path)) {
            $this->addFieldError('path', $this->modx->lexicon('file_folder_err_ns'));
        }
        $name = rawurldecode($this->getProperty('name'));
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

return 'modBrowserFolderRenameProcessor';
