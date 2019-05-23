<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Browser\File;


use MODX\Revolution\Processors\Browser\Browser;

/**
 * Upload files to a directory
 *
 * @property string $path The target directory
 *
 * @package MODX\Revolution\Processors\Browser\File
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
        $path = $this->getProperty('path');
        $path = $path !== '/' ? $this->sanitize($path) : $path;
        if (empty($path)) {
            return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        }
        $response = $this->source->uploadObjectsToContainer($path, $_FILES);

        return $this->handleResponse($response);
    }
}
