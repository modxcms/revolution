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
 * Renames a file
 *
 * @param string $file The file to rename
 * @param string $newname The new name for the file
 *
 * @package modx
 * @subpackage processors.browser
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFileRenameProcessor extends modBrowserProcessor
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

return 'modBrowserFileRenameProcessor';

