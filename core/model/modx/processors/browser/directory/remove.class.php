<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Remove a directory
 *
 * @param string $dir The directory to remove
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFolderRemoveProcessor extends modBrowserProcessor
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
        if (empty($dir)) {
            return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        }
        $dirBases = $this->source->getBases($dir);
        if (in_array($dirBases['pathAbsoluteWithPath'], $this->getProtectedPathDirectories())) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $response = $this->source->removeContainer($dir);

        return $this->handleResponse($response);
    }
}

return 'modBrowserFolderRemoveProcessor';
