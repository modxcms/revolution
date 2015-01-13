<?php
require_once (dirname(dirname(__FILE__)).'/create.class.php');
/**
 * Creates a plugin
 *
 * @param string $name The name of the plugin
 * @param string $plugincode The code of the plugin.
 * @param string $description (optional) A description of the plugin.
 * @param integer $category (optional) The category for the plugin. Defaults to
 * no category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param boolean $disabled (optional) If true, the plugin does not execute.
 * @param string $events (optional) A JSON array of system events to associate
 * this plugin with.
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class modPluginCreateProcessor extends modElementCreateProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin','category','element');
    public $permission = 'new_plugin';
    public $objectType = 'plugin';
    public $beforeSaveEvent = 'OnBeforePluginFormSave';
    public $afterSaveEvent = 'OnPluginFormSave';

    public function beforeSave() {
        $isStatic = intval($this->getProperty('static', 0));

        if ($isStatic == 1) {
            $staticFile = $this->getProperty('static_file');

            if (empty($staticFile)) {
                $this->addFieldError('static_file', $this->modx->lexicon('static_file_ns'));
            }
        }

        return parent::beforeSave();
    }

    public function afterSave() {
        $this->saveEvents();
        return parent::afterSave();
    }

    /**
     * Save system events
     * 
     * @return void
     */
    public function saveEvents() {
        $events = $this->getProperty('events',null);
        if ($events != null) {
            $events = is_array($events) ? $events : $this->modx->fromJSON($events);
            foreach ($events as $id => $event) {
                $properties = array_merge($event,  array(
                    'plugin' => $this->object->get('id'),
                    'event' => $event['name']
                ));
                /** @var modProcessorResponse $response */
                $response = $this->modx->runProcessor('element/plugin/event/update', $properties);
                if ($response->isError()) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $response->getMessage() . print_r($properties, true));
                }
            }
        }
    }
}

return 'modPluginCreateProcessor';
