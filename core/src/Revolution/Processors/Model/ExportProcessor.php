<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Model;


use MODX\Revolution\File\modFileHandler;
use MODX\Revolution\modCacheManager;
use XMLWriter;

/**
 * Utility class for exporting an object
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
abstract class ExportProcessor extends GetProcessor
{
    /** @var string $downloadProperty */
    public $downloadProperty = 'download';
    /** @var string $nameField */
    public $nameField = 'name';
    /** @var XMLWriter $xml */
    public $xml;

    public function cleanup()
    {
        if (!extension_loaded('XMLWriter') || !class_exists('XMLWriter')) {
            return $this->failure($this->modx->lexicon('xmlwriter_err_nf'));
        }

        $download = $this->getProperty($this->downloadProperty);
        if (empty($download)) {
            return $this->cache();
        }

        return $this->download();
    }

    /**
     * Cache the data to an export file
     *
     * @return array|string
     */
    public function cache()
    {
        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->setIndent(true);
        $this->xml->setIndentString('    ');

        $this->prepareXml();

        $this->xml->endDocument();
        $data = $this->xml->outputMemory();

        $f = $this->object->get($this->nameField) . '.xml';
        $fileName = $this->modx->getOption('core_path', null,
                MODX_CORE_PATH) . 'export/' . $this->objectType . '/' . $f;

        /** @var modCacheManager $cacheManager */
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->writeFile($fileName, $data);

        $this->logManagerAction();

        return $this->success($f);
    }

    /**
     * Must be declared in your derivative class. Used to prepare the data to export.
     *
     * @abstract
     */
    abstract public function prepareXml();

    /**
     * Attempt to download the exported file to the browser
     *
     * @return mixed
     */
    public function download()
    {
        $fileName = $this->object->get($this->nameField) . '.xml';
        $file = $this->modx->getOption('core_path', null,
                MODX_CORE_PATH) . 'export/' . $this->objectType . '/' . $fileName;

        /** @var modFileHandler $fileHandler */
        $fileHandler = $this->modx->getService('fileHandler', modFileHandler::class);
        $fileObj = $fileHandler->make($file);
        $name = strtolower(str_replace([' ', '/'], '-', $this->object->get($this->nameField)));

        if (!$fileObj->exists()) {
            return $this->failure($file);
        }

        $o = $fileObj->getContents();

        $fileObj->download(['filename' => $name . '.' . $this->objectType . '.xml']);

        return $o;
    }

    /**
     * Log the export manager action
     *
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_export', $this->classKey,
            $this->object->get($this->primaryKeyField));
    }
}
