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

/**
 * A utility abstract class for defining remove-based processors
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
abstract class RemoveProcessor extends ModelProcessor
{
    /** @var boolean $checkRemovePermission If set to true, will check the remove permission on modAccessibleObjects */
    public $checkRemovePermission = true;
    /** @var string $beforeRemoveEvent The name of the event to fire before removal */
    public $beforeRemoveEvent = '';
    /** @var string $afterRemoveEvent The name of the event to fire after removal */
    public $afterRemoveEvent = '';

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

        if ($this->checkRemovePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('remove')) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }

    public function process()
    {
        $canRemove = $this->beforeRemove();
        if ($canRemove !== true) {
            return $this->failure($canRemove);
        }
        $preventRemoval = $this->fireBeforeRemoveEvent();
        if (!empty($preventRemoval)) {
            return $this->failure($preventRemoval);
        }

        if ($this->removeObject() === false) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_remove'));
        }
        $this->afterRemove();
        $this->fireAfterRemoveEvent();
        $this->logManagerAction();
        $this->cleanup();

        return $this->success('', [$this->primaryKeyField => $this->object->get($this->primaryKeyField)]);
    }

    /**
     * Abstract the removing of the object out to allow for transient and non-persistent object updating in derivative
     * classes
     *
     * @return boolean
     */
    public function removeObject()
    {
        return $this->object->remove();
    }

    /**
     * Can contain pre-removal logic; return false to prevent remove.
     *
     * @return boolean
     */
    public function beforeRemove()
    {
        return !$this->hasErrors();
    }

    /**
     * Can contain post-removal logic.
     *
     * @return bool
     */
    public function afterRemove()
    {
        return true;
    }

    /**
     * Log the removal manager action
     *
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_delete', $this->classKey,
            $this->object->get($this->primaryKeyField));
    }

    /**
     * After removal, manager action log, and event firing logic
     *
     * @return void
     */
    public function cleanup()
    {
    }

    /**
     * If specified, fire the before remove event
     *
     * @return boolean Return false to allow removal; non-empty to prevent it
     */
    public function fireBeforeRemoveEvent()
    {
        $preventRemove = false;
        if (!empty($this->beforeRemoveEvent)) {
            $response = $this->modx->invokeEvent($this->beforeRemoveEvent, [
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->objectType => &$this->object,
                'object' => &$this->object,
            ]);
            $preventRemove = $this->processEventResponse($response);
        }

        return $preventRemove;
    }

    /**
     * If specified, fire the after remove event
     *
     * @return void
     */
    public function fireAfterRemoveEvent()
    {
        if (!empty($this->afterRemoveEvent)) {
            $this->modx->invokeEvent($this->afterRemoveEvent, [
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->objectType => &$this->object,
                'object' => &$this->object,
            ]);
        }
    }
}
