<?php

/**
 * Upload files to a directory
 *
 * @param string $path The target directory
 *
 * @package modx
 * @subpackage processors.browser.file
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFileUploadProcessor extends modBrowserProcessor
{
    public $permission = 'file_upload';
    public $policy = 'create';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $path = rawurldecode($this->getProperty('path'));
        if (empty($path)) {
            return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        }
        $response = $this->source->uploadObjectsToContainer($path, $_FILES);

        return $this->handleResponse($response);
    }
}

return 'modBrowserFileUploadProcessor';
