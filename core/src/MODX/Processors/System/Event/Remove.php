<?php

namespace MODX\Processors\System\Event;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a system even
 *
 * @param string $name The nameof the event
 *
 * @package modx
 * @subpackage processors.system.events
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modEvent';
    public $languageTopics = ['events'];
    public $permission = 'events';
    public $objectType = 'event';
    public $primaryKeyField = 'name';


    public function beforeRemove()
    {
        $service = $this->object->get('service');
        if ($service != 6) {
            return $this->modx->lexicon('system_events_err_remove_not_allowed');
        }

        return parent::beforeRemove();
    }
}