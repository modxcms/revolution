<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Plugin\Event;


use MODX\Revolution\Processors\Model\GetProcessor;
use MODX\Revolution\modPluginEvent;

/**
 * Get Plugin event
 *
 * @property int $plugin Plugin primary key
 * @property int $event  Event primary key
 *
 * @package MODX\Revolution\Processors\Element\Plugin\Event
 */
class Get extends GetProcessor
{
    public $classKey = modPluginEvent::class;
    public $objectType = 'plugin_event';
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
