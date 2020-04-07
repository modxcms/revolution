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


use MODX\Revolution\modEvent;
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modPluginEvent;

/**
 * Associate the event to the plugins.
 *
 * @property string $name    The name of the event.
 * @property string $plugins JSON string of the form [{"id":1,"name":"PluginName","priority":"0","propertyset":"0","menu":null},{...},...].
 *
 * @package MODX\Revolution\Processors\Element\Plugin\Event
 */
class Associate extends UpdateProcessor
{
    public $classKey = modEvent::class;
    public $primaryKeyField = 'name';
    public $languageTopics = ['plugin', 'system_events'];
    public $permission = 'save_plugin';
    public $objectType = 'plugin_event';
    public $checkSavePermission = true;

    public function beforeSave()
    {
        /* get plugins */
        $plugins = $this->modx->fromJSON($this->getProperty('plugins'));

        $eventName = $this->object->get('name');

        $this->modx->removeCollection(modPluginEvent::class, [
            'event' => $eventName,
        ]);

        foreach ($plugins as $pluginArray) {
            if (empty($pluginArray['id'])) {
                continue;
            }

            $pluginEvent = $this->modx->newObject(modPluginEvent::class);
            $pluginEvent->set('event', $eventName);
            $pluginEvent->set('pluginid', $pluginArray['id']);
            $priority = (!empty($pluginArray['priority']) ? $pluginArray['priority'] : 0);
            $pluginEvent->set('priority', (int)$priority);
            $pluginEvent->set('propertyset',
                (int)(!empty($pluginArray['propertyset']) ? $pluginArray['propertyset'] : 0));

            if (!$pluginEvent->save()) {
                $this->addFieldError('plugin_event_err_save',
                    $this->modx->lexicon('plugin_event_err_save') . print_r($pluginEvent->toArray(), true));
            }
        }

        return parent::beforeSave();
    }

    /**
     * Return the success message
     *
     * @return array
     */
    public function cleanup()
    {
        return $this->success();
    }
}
