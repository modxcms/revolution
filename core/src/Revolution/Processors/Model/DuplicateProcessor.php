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


use MODX\Revolution\modAccessibleObject;
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\Sources\modFileMediaSource;
use xPDO\Om\xPDOObject;

/**
 * A utility abstract class for defining duplicate-based processors
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
abstract class DuplicateProcessor extends ModelProcessor
{
    /** @var boolean $checkSavePermission Whether or not to check the save permission on modAccessibleObjects */
    public $checkSavePermission = true;
    /** @var xPDOObject $newObject The newly duplicated object */
    public $newObject;
    public $nameField = 'name';
    public $staticfileField = 'static_file';
    /**
     * @var string $newNameField The name of field that used for filling new name of object.
     * If defined, duplication error will be attached to field with this name
     */
    public $newNameField;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $this->object = $this->modx->getObject($this->classKey, $primaryKey);
        if (empty($this->object)) {
            return $this->modx->lexicon($this->objectType . '_err_nfs', [$this->primaryKeyField => $primaryKey]);
        }

        if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }

        $this->newObject = $this->modx->newObject($this->classKey);

        return parent::initialize();
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        /* Run the beforeSet method before setting the fields, and allow stoppage */
        $canSave = $this->beforeSet();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }

        $this->newObject->fromArray($this->object->toArray());
        $name = $this->getNewName();
        $this->setNewName($name);

        $staticFilename = $this->getProperty($this->staticfileField);
        if (!empty($staticFilename)) {
            $this->newObject->set('static_file', $staticFilename);
        }

        if ($this->alreadyExists($name)) {
            $this->addFieldError(
                $this->newNameField ? $this->newNameField : $this->nameField,
                $this->modx->lexicon($this->objectType . '_err_ae', ['name' => $name])
            );
        }

        /* Check if a static file already exists within specified static file path. */
        if ($staticFilename && $this->staticFileAlreadyExists($staticFilename)) {
            $this->modx->lexicon->load('core:element');
            $this->addFieldError($this->staticfileField, $this->modx->lexicon('element_err_staticfile_exists'));
        }

        $canSave = $this->beforeSave();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }

        /* save new object */
        if ($this->saveObject() === false) {
            $this->modx->error->checkValidation($this->newObject);

            return $this->failure($this->modx->lexicon($this->objectType . '_err_duplicate'));
        }

        $this->afterSave();
        $this->logManagerAction();

        return $this->cleanup();
    }

    /**
     * Abstract the saving of the object out to allow for transient and non-persistent object updating in derivative
     * classes
     *
     * @return boolean
     */
    public function saveObject()
    {
        return $this->newObject->save();
    }

    /**
     * Cleanup and return a response.
     *
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->newObject);
    }

    /**
     * Override in your derivative class to do functionality before the fields are set on the object
     *
     * @return boolean
     */
    public function beforeSet()
    {
        return !$this->hasErrors();
    }

    /**
     * Run any logic before the object has been duplicated. May return false to prevent duplication.
     *
     * @return boolean
     */
    public function beforeSave()
    {
        return !$this->hasErrors();
    }

    /**
     * Run any logic after the object has been duplicated
     *
     * @return boolean
     */
    public function afterSave()
    {
        return true;
    }

    /**
     * Get the new name for the duplicate
     *
     * @return string
     */
    public function getNewName()
    {
        $name = $this->getProperty($this->nameField);
        $newName = !empty($name) ? $name : $this->modx->lexicon('duplicate_of',
            ['name' => $this->object->get($this->nameField)]);

        return $newName;
    }

    /**
     * Set the new name to the new object
     *
     * @param string $name
     *
     * @return string
     */
    public function setNewName($name)
    {
        return $this->newObject->set($this->nameField, $name);
    }

    /**
     * Check to see if an object already exists with that name
     *
     * @param string $name
     *
     * @return boolean
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount($this->classKey, [
                $this->nameField => $name,
            ]) > 0;

    }

    /**
     * Check to see if a static element file already exists.
     *
     * @param $filename
     *
     * @return bool
     */
    public function staticFileAlreadyExists($filename)
    {
        $sourceId = $this->getProperty('source');
        if ($sourceId > 0) {
            /** @var modFileMediaSource $source */
            $source = $this->modx->getObject(modFileMediaSource::class, ['id' => $sourceId]);
            if ($source && $source->get('is_stream')) {
                $source->initialize();
                $filename = $source->getBasePath() . $filename;
            }
        }

        return file_exists($filename);
    }

    /**
     * Log a manager action
     *
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_duplicate', $this->classKey, $this->newObject->get('id'));
    }
}
