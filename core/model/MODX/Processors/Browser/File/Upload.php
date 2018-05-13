<?php

namespace MODX\Processors\Browser\File;

use MODX\Processors\Browser\Browser;

/**
 * Upload files to a directory
 *
 * @param string $path The target directory
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class Upload extends Browser
{
    public $permission = 'file_upload';
    public $policy = 'create';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $path = $this->sanitize($this->getProperty('path'));
        if (empty($path)) {
            return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        }
        $response = $this->source->uploadObjectsToContainer($path, $_FILES);

        return $this->handleResponse($response);
    }
}
