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
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\modX;

/**
 * Update a plugin.
 *
 * @property integer $id          The ID of the plugin.
 * @property string  $name        The name of the plugin.
 * @property string  $plugincode  The code of the plugin.
 * @property string  $description (optional) A description of the plugin.
 * @property integer $category    (optional) The category for the plugin. Defaults to
 * no category.
 * @property boolean $locked      (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @property boolean $disabled    (optional) If true, the plugin does not execute.
 * @property string  $events      (optional) A JSON array of system events to associate
 * this plugin with.
 *
 * @package MODX\Revolution\Processors\Element\Plugin
 */
class Update extends \MODX\Revolution\Processors\Element\Update
{
    public $classKey = modPlugin::class;
    public $languageTopics = ['plugin', 'category', 'element'];
    public $permission = 'save_plugin';
    public $objectType = 'plugin';
    public $beforeSaveEvent = 'OnBeforePluginFormSave';
    public $afterSaveEvent = 'OnPluginFormSave';

    public function beforeSave()
    {
        $disabled = (boolean)$this->getProperty('disabled', false);
        $this->object->set('disabled', $disabled);

        $isStatic = intval($this->getProperty('static', 0));

        if ($isStatic == 1) {
            $staticFile = $this->getProperty('static_file');

            if (empty($staticFile)) {
                $this->addFieldError('static_file', $this->modx->lexicon('static_file_ns'));
            }
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->setSystemEvents();
        parent::afterSave();
    }

    /**
     * Update system event associations
     *
     * @return void
     */
    public function setSystemEvents()
    {
        $events = $this->getProperty('events', null);
        if ($events !== null) {
            $pluginEvents = is_array($events) ? $events : $this->modx->fromJSON($events);
            foreach ($pluginEvents as $id => $event) {
                $properties = array_merge($event, [
                    'plugin' => $this->object->get('id'),
                    'event' => $event['name'],
                ]);
                /** @var ProcessorResponse $response */
                $response = $this->modx->runProcessor(Event\Update::class, $properties);
                if ($response->isError()) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $response->getMessage() . print_r($properties, true));
                }
            }
        }
    }

    public function cleanup()
    {
        return $this->success('', array_merge(
            $this->object->get(['id', 'name', 'description', 'locked', 'category', 'disabled', 'plugincode']),
            ['previous_category' => $this->previousCategory]
        ));
    }
}
