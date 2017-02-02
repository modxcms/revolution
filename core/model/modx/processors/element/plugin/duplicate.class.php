<?php
require_once (dirname(__DIR__).'/duplicate.class.php');
/**
 * Duplicate a plugin
 *
 * @param integer $id The ID of the plugin
 * @param string $name The new name of the duplicated plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class modPluginDuplicateProcessor extends modElementDuplicateProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin');
    public $permission = 'new_plugin';
    public $objectType = 'plugin';

    public function afterSave() {
        $this->duplicateSystemEvents();
        return parent::afterSave();
    }
    
    public function duplicateSystemEvents() {
        $events = $this->object->getMany('PluginEvents');
        if (is_array($events) && !empty($events)) {
            /** @var modPluginEvent $event */
            foreach($events as $event) {
                $properties = $event->toArray();
                $properties['plugin'] = $this->newObject->get('id');
                $properties['enabled'] = 1;
                /** @var modProcessorResponse $response */
                $response = $this->modx->runProcessor('element/plugin/event/update', $properties);
                if ($response->isError()) {
                    $this->newObject->remove();
                    return $this->failure($this->modx->lexicon('plugin_event_err_duplicate') . ': ' . $response->getMessage() . print_r($properties,1));
                }
            }
        }
        return $events;
    }
}

return 'modPluginDuplicateProcessor';
