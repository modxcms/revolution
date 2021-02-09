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
 * Renames a directory
 *
 * @property string $path The directory to rename
 * @property string $name The new name for the directory
 *
 * @package MODX\Revolution\Processors\Browser\Directory
 */
class Rename extends Browser
{
    public $permission = 'directory_update';
    public $policy = 'save';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $path = $this->sanitize($this->getProperty('path'));
        if (!strlen($path)) {
            $this->addFieldError('path', $this->modx->lexicon('file_folder_err_ns'));
        }
        $name = $this->sanitize($this->getProperty('name'));
        if (!strlen($name)) {
            $this->addFieldError('name', $this->modx->lexicon('name_err_ns'));
        }
        $pathBases = $this->source->getBases($path);
        if (in_array($pathBases['pathAbsoluteWithPath'], $this->getProtectedPathDirectories())) {
            return $this->failure($this->modx->lexicon('file_folder_err_rename_protected'));
        }
        if ($this->hasErrors()) {
            return $this->failure();
        }
        $response = $this->source->renameContainer($path, $name);

        return $this->handleResponse($response);
    }
}
