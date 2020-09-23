<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Packages;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Upload transport package to Packages directory
 * @param string $file The transport package to upload
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class Upload extends Processor
{
    /** @var modMediaSource $source */
    public $source;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_upload');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['file'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        if (empty($_FILES)) {
            return $this->modx->lexicon('no_file_err');
        }
        $this->getSource();
        $this->setProperty('files', $_FILES);
        return true;
    }

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
    public function getSource()
    {
        $this->source = modMediaSource::getDefaultSource($this->modx);
        if ($this->source === null || !$this->source->getWorkingContext()) {
            return false;
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();

        return $this->source;
    }

    public function process()
    {

        // Even though we're not using media sources, it seems like the
        // easiest way to check permissions (and waste time/effort/memory)
        $this->source->initialize();
        if (!$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        // Prepare the upload path and check it exists
        $destination = $this->modx->getOption('core_path') . 'packages/';
        if (!is_dir($destination)) {
            return $this->failure($this->modx->lexicon('file_package_err_invalid_directory'));
        }

        // Grab the file
        $file = $this->getProperty('files');
        $file = array_shift($file);

        // Check MIME type of file
        if (!in_array(strtolower($file['type']), [
            'application/zip',
            'application/x-zip-compressed',
            'application/x-zip',
            'application/octet-stream',
        ])) {
            return $this->failure($this->modx->lexicon('file_package_err_invalid_type'));
        }

        // Check valid name of file
        if (!preg_match("/.+\\.transport\\.zip$/i", $file['name'])) {
            return $this->failure($this->modx->lexicon('file_package_err_invalid_name', [
                'name' => $file['name']
            ]));
        }

        $newPath = $destination . $file['name'];

        // Check if a file exists
        if (file_exists($newPath)) {
            return $this->failure(sprintf($this->modx->lexicon('file_err_ae'), $file['name']));
        }

        // Return response
        if (move_uploaded_file($file['tmp_name'], $newPath)) {
            return $this->success();
        }

        return $this->failure($this->modx->lexicon('unknown_error'));

    }
}
