<?php

namespace MODX\Processors\Element\Plugin\Event;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove Event from a Plugin
 *
 * @param int $plugin Plugin primary key
 * @param int $event Event primary key
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
class Remove extends modObjectRemoveProcessor
{
    public $objectType = 'plugin_event';
    public $classKey = 'modPluginEvent';
    public $permission = 'delete_plugin';
    public $languageTopics = ['plugin'];


    public function initialize()
    {
        $plugin = $this->getProperty('plugin', 0);
        $event = $this->getProperty('event', 0);
        if (!$plugin || !$event) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, [
            'pluginid' => $plugin,
            'event' => $event,
        ]);

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        return true;
    }

}
