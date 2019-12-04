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
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\Validation\modValidator;

/**
 * A utility abstract class for defining update-based processors
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
abstract class UpdateProcessor extends ModelProcessor
{
    public $checkSavePermission = true;
    /** @var string $beforeSaveEvent The name of the event to fire before saving */
    public $beforeSaveEvent = '';
    /** @var string $afterSaveEvent The name of the event to fire after saving */
    public $afterSaveEvent = '';

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

        $this->object->fromArray($this->getProperties());

        /* Run the beforeSave method and allow stoppage */
        $canSave = $this->beforeSave();
        if ($canSave !== true) {
            return $this->failure($canSave);
        }

        /* run object validation */
        if (!$this->object->validate()) {
            /** @var modValidator $validator */
            $validator = $this->object->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
                }
            }
        }

        /* run the before save event and allow stoppage */
        $preventSave = $this->fireBeforeSaveEvent();
        if (!empty($preventSave)) {
            return $this->failure($preventSave);
        }

        if ($this->saveObject() === false) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_save'));
        }
        $this->afterSave();
        $this->fireAfterSaveEvent();
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
        return $this->object->save();
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
     * Override in your derivative class to do functionality before save() is run
     *
     * @return boolean
     */
    public function beforeSave()
    {
        return !$this->hasErrors();
    }

    /**
     * Override in your derivative class to do functionality after save() is run
     *
     * @return boolean
     */
    public function afterSave()
    {
        return true;
    }

    /**
     * Return the success message
     *
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->object);
    }


    /**
     * Fire before save event. Return true to prevent saving.
     *
     * @return boolean
     */
    public function fireBeforeSaveEvent()
    {
        $preventSave = false;
        if (!empty($this->beforeSaveEvent)) {
            /** @var boolean|array $OnBeforeFormSave */
            $OnBeforeFormSave = $this->modx->invokeEvent($this->beforeSaveEvent, [
                'mode' => modSystemEvent::MODE_UPD,
                'data' => $this->object->toArray(),
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->objectType => &$this->object,
                'object' => &$this->object,
            ]);
            if (is_array($OnBeforeFormSave)) {
                $preventSave = false;
                foreach ($OnBeforeFormSave as $msg) {
                    if (!empty($msg)) {
                        $preventSave .= $msg . "\n";
                    }
                }
            } else {
                $preventSave = $OnBeforeFormSave;
            }
        }

        return $preventSave;
    }

    /**
     * Fire the after save event
     *
     * @return void
     */
    public function fireAfterSaveEvent()
    {
        if (!empty($this->afterSaveEvent)) {
            $this->modx->invokeEvent($this->afterSaveEvent, [
                'mode' => modSystemEvent::MODE_UPD,
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->objectType => &$this->object,
                'object' => &$this->object,
            ]);
        }
    }

    /**
     * Log the removal manager action
     *
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_update', $this->classKey,
            $this->object->get($this->primaryKeyField));
    }

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function doesAlreadyExist(array $criteria)
    {
        return $this->modx->getCount($this->classKey, $criteria);
    }
}
