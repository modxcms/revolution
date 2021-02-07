<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Browser\Directory;


use MODX\Revolution\Processors\Browser\Browser;

/**
 * Create a directory.
 *
 * @property string  $name        The name of the directory to create
 * @property string  $parent      The parent directory
 *
 * @package MODX\Revolution\Processors\Browser\Directory
 */
class Create extends Browser
{
    public $permission = 'directory_create';
    public $policy = 'create';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $parent = $this->sanitize($this->getProperty('parent', ''));
        $name = $this->sanitize($this->getProperty('name'));
        if (!strlen($name)) {
            return $this->modx->lexicon('file_folder_err_ns_name');
        }
        $response = $this->source->createContainer($name, $parent);

        return $this->handleResponse($response);
    }
}
