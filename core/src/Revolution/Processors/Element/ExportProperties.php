<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\File\modFileHandler;
use MODX\Revolution\Processors\Processor;

/**
 * Export properties and output url to download to browser
 *
 * @package MODX\Revolution\Processors\Element
 */
class ExportProperties extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_propertyset');
    }

    public function getLanguageTopics()
    {
        return ['propertyset', 'element'];
    }

    public function process()
    {
        $response = false;
        $download = $this->getProperty('download');

        if (!empty($download)) {
            $response = $this->download();
        }

        return $response;
    }

    /**
     * Create a temporary file object and serve as a download to the browser
     *
     * @return bool|string
     */
    public function download()
    {
        $data = $this->getProperty('data');

        if (empty($data)) {
            return $this->failure($this->modx->lexicon('propertyset_err_ns'));
        }

        /** @var modFileHandler $fileHandler */
        $fileHandler = $this->modx->getService('fileHandler', modFileHandler::class);

        $fileName = strtolower(str_replace(' ', '-', $this->getProperty('id'))) . '.export.js';

        $fileobj = $fileHandler->make($this->modx->getOption('core_path', null,
                MODX_CORE_PATH) . 'export/properties/' . $fileName);

        $fileobj->setContent($data);
        $fileobj->download();

        return true;
    }
}
