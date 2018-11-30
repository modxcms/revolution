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
 * Updates a file.
 *
 * @param string $file The absolute path of the file
 * @param string $name Will rename the file if different
 * @param string $content The new content of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFileUpdateProcessor extends modBrowserProcessor
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

return 'modBrowserFileUpdateProcessor';
