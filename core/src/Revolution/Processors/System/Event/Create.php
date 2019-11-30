<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Event;

use MODX\Revolution\modEvent;
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Create a system event
 * @package MODX\Revolution\Processors\System\Event
 */
class Create extends CreateProcessor
{
    public $classKey = modEvent::class;
    public $languageTopics = ['events'];
    public $permission = 'events';
    public $objectType = 'event';
    public $primaryKeyField = 'name';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        /* prevent empty or already existing settings */
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('system_events_err_ns'));
        }
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('system_events_err_ae'));
        }

        /* prevent keys starting with numbers */
        if (is_numeric($name[0])) {
            $this->addFieldError('name', $this->modx->lexicon('system_events_err_startint'));
        }
        $this->object->set('name', $name);

        return !$this->hasErrors();

    }

    /**
     * Check to see if a Event already exists with this name
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount(modEvent::class, [
                'name' => $name,
            ]) > 0;
    }
}
