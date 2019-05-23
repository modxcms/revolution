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
 * Updates a file.
 *
 * @property string $file    The absolute path of the file
 * @property string $name    Will rename the file if different
 * @property string $content The new content of the file
 *
 * @package MODX\Revolution\Processors\Browser\File
 */
class Update extends Browser
{
    public $permission = 'file_update';
    public $policy = 'save';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $file = $this->sanitize($this->getProperty('file'));
        if (empty($file)) {
            return $this->failure($this->modx->lexicon('file_err_ns'));
        }
        $response = $this->source->updateObject($file, $this->getProperty('content', ''));

        return $this->handleResponse($response);
    }
}
