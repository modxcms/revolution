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
        $cookieName = $this->getProperty('cookieName');
        if ($cookieName) {
            setcookie($cookieName, 'true', time() + 10, '/');
        }

        if (!extension_loaded('XMLWriter') || !class_exists('XMLWriter')) {
            return $this->failure($this->modx->lexicon('xmlwriter_err_nf'));
        }

        return $this->download();
    }

    /**
     * Create a temporary file object and serve as a download to the browser
     *
     * @return array|string
     */
    public function download()
    {
        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->setIndent(true);
        $this->xml->setIndentString('    ');

        $this->prepareXml();

        $this->xml->endDocument();
        $data = $this->xml->outputMemory();

        $this->logManagerAction();

        /** @var modFileHandler $fileHandler */
        $fileHandler = $this->modx->getService('fileHandler', modFileHandler::class);

        $fileName = strtolower(str_replace(' ', '-', $this->object->get($this->nameField))) . '.' . $this->objectType . '.xml';

        $fileobj = $fileHandler->make($this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'export/' . $this->objectType . '/' . $fileName);

        $fileobj->setContent($data);
        $fileobj->download([
            'mimetype' => 'text/xml'
        ]);

        return true;
    }

    /**
     * Must be declared in your derivative class. Used to prepare the data to export.
     *
     * @abstract
     */
    abstract public function prepareXml();

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
