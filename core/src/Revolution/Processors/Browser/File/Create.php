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
 * Creates a file.
 *
 * @property string $file    The absolute path of the file
 * @property string $name    The name of the file
 * @property string $content The content of the file
 *
 * @package MODX\Revolution\Processors\Browser\File
 */
class Create extends Browser
{
    public $permission = 'file_create';
    public $policy = 'create';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $directory = $this->sanitize($this->getProperty('directory', ''));
        $name = $this->sanitize($this->getProperty('name'));
        if (empty($name)) {
            return $this->failure($this->modx->lexicon('file_err_ns'));
        }
        $response = $this->source->createObject($directory, $name, $this->getProperty('content'));

        return empty($response)
            ? $this->handleResponse($response)
            : $this->success('', [
                'file' => rawurlencode($directory . ltrim($name, '/')),
            ]);
    }
}
