<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Browser;


/**
 * Set visibility on a directory or file
 *
 * @property string  $mode        The mode to chmod to
 * @property string  $dir         The absolute path of the dir
 *
 * @package MODX\Revolution\Processors\Browser
 */
class Visibility extends Browser
{
    public $permission = 'directory_chmod';
    public $policy = 'save';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $visibility = $this->getProperty('visibility');
        if (empty($visibility)) {
            $this->addFieldError('visibility', $this->modx->lexicon('file_folder_visibility_err_ns'));
        }
        $path = $this->sanitize($this->getProperty('path'));
        if (empty($path)) {
            $this->addFieldError('path', $this->modx->lexicon('file_folder_err_ns'));
        }
        if ($this->hasErrors()) {
            return $this->failure();
        }
        $response = $this->source->setVisibility($path, $visibility);

        return $this->handleResponse($response);
    }
}
