<?php

namespace MODX\Processors\Element;

use MODX\modFileHandler;
use MODX\Processors\modProcessor;

/**
 * Export properties and output url to download to browser
 *
 * @package modx
 * @subpackage processors.element
 */
class ExportProperties extends modProcessor
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
        $fileHandler = new modFileHandler($this->modx);
        $fileName = strtolower(str_replace(' ', '-', $this->getProperty('id'))) . '.export.js';

        $fileobj = $fileHandler->make($this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'export/properties/' . $fileName);

        $fileobj->setContent($data);
        $fileobj->download();

        return true;
    }
}