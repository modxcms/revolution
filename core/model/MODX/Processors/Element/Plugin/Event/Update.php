<?php

namespace MODX\Processors\Element\Plugin\Event;

use MODX\Processors\modObjectProcessor;

/**
 * Update Plugin Event
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
class Update extends modObjectProcessor
{
    public $objectType = 'plugin_event';
    public $classKey = 'modPluginEvent';
    public $permission = 'save_plugin';
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
            if ($this->getProperty('enabled')) {
                $this->object = $this->modx->newObject($this->classKey);
                $this->object->set('pluginid', $plugin);
                $this->object->set('event', $event);
            } else {
                return $this->modx->lexicon($this->objectType . '_err_nf');
            }
        }

        return true;
    }


    public function process()
    {
        if ($this->getProperty('enabled')) {
            $this->object->set('priority', (int)$this->getProperty('priority', 0));
            $this->object->set('propertyset', (int)$this->getProperty('propertyset', 0));

            if (!$this->object->save()) {
                return $this->failure($this->modx->lexicon($this->objectType . '_err_save'));
            }
        } else {
            if (!$this->object->remove()) {
                return $this->failure($this->modx->lexicon($this->objectType . '_err_remove'));
            }
        }

        return $this->success('', $this->object);
    }


    public function beforeSet()
    {
        $this->setDefaultProperties([
            'priority' => 0,
        ]);
    }
}
