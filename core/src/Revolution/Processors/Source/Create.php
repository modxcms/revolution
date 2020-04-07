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

use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Creates a Media Source
 * @package MODX\Revolution\Processors\Source
 */
class Create extends CreateProcessor
{
    public $classKey = modMediaSource::class;
    public $languageTopics = ['source'];
    public $permission = 'source_save';
    public $objectType = 'source';
    public $beforeSaveEvent = 'OnMediaSourceBeforeFormSave';
    public $afterSaveEvent = 'OnMediaSourceFormSave';

    /**
     * @return bool
     */
    public function initialize()
    {
        $classKey = $this->getProperty('class_key');
        if (empty($classKey)) {
            $this->setProperty('class_key', modFileMediaSource::class);
        }
        return parent::initialize();
    }

    /**
     * Validate the properties sent
     * @return boolean
     */
    public function beforeSave()
    {
        /* validate name field */
        $name = $this->object->get('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('source_err_ns_name'));
        } else if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('source_err_ae_name', [
                'name' => $name,
            ]));
        }

        return !$this->hasErrors();
    }

    /**
     * Check to see if a Media Source with the specified name already exists
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount(modMediaSource::class, [
                'name' => $name,
            ]) > 0;
    }
}
