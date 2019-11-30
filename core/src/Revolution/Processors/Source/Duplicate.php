<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Source;

use MODX\Revolution\Processors\Model\DuplicateProcessor;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Duplicates a source.
 * @param integer $id The source to duplicate
 * @param string $name The name of the new source.
 * @package MODX\Revolution\Processors\Source
 */
class Duplicate extends DuplicateProcessor
{
    public $classKey = modMediaSource::class;
    public $languageTopics = ['source'];
    public $permission = 'source_save';
    public $objectType = 'source';
    public $checkSavePermission = false;

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        if (!$this->object->checkPolicy('copy')) {
            return $this->modx->lexicon('access_denied');
        }
        return $initialized;
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->fireDuplicateEvent();
        return parent::afterSave();
    }

    /**
     * Fire the OnMediaSourceDuplicate event
     * @return void
     */
    public function fireDuplicateEvent()
    {
        $this->modx->invokeEvent('OnMediaSourceDuplicate', [
            'newResource' => &$this->newObject,
            'oldResource' => &$this->object,
            'newName' => $this->getProperty($this->nameField, ''),
        ]);
    }
}
