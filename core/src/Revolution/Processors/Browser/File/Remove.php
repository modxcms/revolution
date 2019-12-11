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
 * Removes a file.
 *
 * @property string  $file        The name of the file.
 *
 * @package MODX\Revolution\Processors\Browser\File
 */
class Remove extends Browser
{
    public $permission = 'file_remove';
    public $policy = 'remove';
    public $languageTopics = ['file'];


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $file = $this->sanitize($this->getProperty('file'));
        if (empty($file)) {
            return $this->failure($this->modx->lexicon('file_err_ns'));
        }
        $response = $this->source->removeObject($file);

        return $this->handleResponse($response);
    }
}
