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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Remove a system even
 * @param string $name The name of the event
 * @package MODX\Revolution\Processors\System\Event
 */
class Remove extends RemoveProcessor
{
    public $classKey = modEvent::class;
    public $languageTopics = ['events'];
    public $permission = 'events';
    public $objectType = 'event';
    public $primaryKeyField = 'name';

    /**
     * @return bool|string|null
     */
    public function beforeRemove()
    {
        $service = $this->object->get('service');
        if ($service !== 6) {
            return $this->modx->lexicon('system_events_err_remove_not_allowed');
        }

        return parent::beforeRemove();
    }
}
