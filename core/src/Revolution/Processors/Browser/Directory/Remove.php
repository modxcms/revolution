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
 * Remove a directory
 *
 * @property string  $dir         The directory to remove
 *
 * @package MODX\Revolution\Processors\Browser\Directory
 */
class Remove extends Browser
{
    public $permission = 'directory_remove';
    public $policy = 'remove';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $dir = $this->sanitize($this->getProperty('dir'));
        if (!strlen($dir)) {
            return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        }
        $dirBases = $this->source->getBases($dir);
        if (in_array($dirBases['pathAbsoluteWithPath'], $this->getProtectedPathDirectories())) {
            return $this->failure($this->modx->lexicon('file_folder_err_remove_protected'));
        }
        $response = $this->source->removeContainer($dir);

        return $this->handleResponse($response);
    }
}
