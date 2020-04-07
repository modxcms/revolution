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
 * Renames a file
 *
 * @property string $file    The file to rename
 * @property string $newname The new name for the file
 *
 * @package MODX\Revolution\Processors\Browser\File
 */
class Rename extends Browser
{
    public $permission = 'file_update';
    public $policy = 'save';
    public $languageTopics = ['file'];


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $oldFile = $this->sanitize($this->getProperty('path'));
        if (empty($oldFile)) {
            $this->addFieldError('path', $this->modx->lexicon('file_err_ns'));
        }
        $name = $this->sanitize($this->getProperty('name'));
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('name_err_ns'));
        }
        if ($this->hasErrors()) {
            return $this->failure();
        }
        $response = $this->source->renameObject($oldFile, $name);

        return $this->handleResponse($response);
    }
}
