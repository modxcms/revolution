<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Plugin;


use MODX\Revolution\modPlugin;
use MODX\Revolution\modPluginEvent;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\Element\Plugin\Event\Update;

/**
 * Duplicate a plugin
 *
 * @property integer $id   The ID of the plugin
 * @property string  $name The new name of the duplicated plugin
 *
 * @package MODX\Revolution\Processors\Element\Plugin
 */
class Duplicate extends \MODX\Revolution\Processors\Element\Duplicate
{
    public $classKey = modPlugin::class;
    public $languageTopics = ['plugin'];
    public $permission = 'new_plugin';
    public $objectType = 'plugin';

    public function afterSave()
    {
        $this->duplicateSystemEvents();

        return parent::afterSave();
    }

    public function duplicateSystemEvents()
    {
        $events = $this->object->getMany('PluginEvents');
        if (is_array($events) && !empty($events)) {
            /** @var modPluginEvent $event */
            foreach ($events as $event) {
                $properties = $event->toArray();
                $properties['plugin'] = $this->newObject->get('id');
                $properties['enabled'] = 1;
                /** @var ProcessorResponse $response */
                $response = $this->modx->runProcessor(Update::class, $properties);
                if ($response->isError()) {
                    $this->newObject->remove();

                    return $this->failure($this->modx->lexicon('plugin_event_err_duplicate') . ': ' . $response->getMessage() . print_r($properties,
                            1));
                }
            }
        }

        return $events;
    }
}
