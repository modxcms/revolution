<?php

namespace MODX\Processors\Element\Plugin\Event;

use MODX\Processors\modObjectGetProcessor;

/**
 * Get Plugin event
 *
 * @param int $plugin Plugin primary key
 * @param int $event Event primary key
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
class Get extends modObjectGetProcessor
{
    public $objectType = 'plugin_event';
    public $classKey = 'modPluginEvent';
    public $permission = 'view_plugin';
    public $languageTopics = ['plugin'];


    /**
     * {@inheritDoc}
     * @return boolean
     */
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
