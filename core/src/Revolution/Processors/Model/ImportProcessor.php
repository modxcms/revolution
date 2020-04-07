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


use MODX\Revolution\Processors\ModelProcessor;
use SimpleXMLElement;

/**
 * Utility class for importing an object
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
abstract class ImportProcessor extends ModelProcessor
{
    /** @var string $nameField The name, or unique, field for the object */
    public $nameField = 'name';
    /** @var boolean $setName Whether or not to attempt to set the name field */
    public $setName = true;
    /** @var string $fileProperty The property that contains the file data */
    public $fileProperty = 'file';
    /** @var SimpleXMLElement $xml The parsed XML from the file */
    public $xml = '';

    public function initialize()
    {
        $file = $this->getProperty($this->fileProperty);
        if (empty($file) || !isset($file['tmp_name'])) {
            return $this->modx->lexicon('import_err_upload');
        }
        if ($file['error'] !== 0) {
            return $this->modx->lexicon('import_err_upload');
        }
        if (!file_exists($file['tmp_name'])) {
            return $this->modx->lexicon('import_err_upload');
        }

        $this->xml = file_get_contents($file['tmp_name']);
        if (empty($this->xml)) {
            return $this->modx->lexicon('import_err_upload');
        }

        if (!function_exists('simplexml_load_string')) {
            return $this->failure($this->modx->lexicon('simplexml_err_nf'));
        }

        return parent::initialize();
    }

    public function process()
    {
        /** @var SimpleXmlElement $xml */
        $this->xml = @simplexml_load_string($this->xml);
        if (empty($this->xml)) {
            return $this->failure($this->modx->lexicon('import_err_xml'));
        }

        $this->object = $this->modx->newObject($this->classKey);

        if ($this->setName) {
            $name = (string)$this->xml->name;
            if ($this->alreadyExists($name)) {
                $this->object->set($this->nameField, $this->modx->lexicon('duplicate_of', ['name' => $name]));
            } else {
                $this->object->set($this->nameField, $name);
            }
        }

        $canSave = $this->beforeSave();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }

        if (!$this->object->save()) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_save'));
        }

        $this->afterSave();
        $this->logManagerAction();

        return $this->success();
    }

    /**
     * Do any before save logic
     *
     * @return boolean
     */
    public function beforeSave()
    {
        return !$this->hasErrors();
    }

    /**
     * Do any after save logic
     *
     * @return boolean
     */
    public function afterSave()
    {
        return !$this->hasErrors();
    }

    /**
     * Check to see if the object already exists with this name field
     *
     * @param string $name
     *
     * @return bool
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount($this->classKey, [$this->nameField => $name]) > 0;
    }

    /**
     * Log the export manager action
     *
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_import', $this->classKey,
            $this->object->get($this->primaryKeyField));
    }
}
