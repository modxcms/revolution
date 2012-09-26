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
 * @param json $events (optional) A json array of system events to associate
 * this plugin with.
 * @param json $propdata (optional) A json array of properties
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
                if (!empty($event['enabled'])) {
                    /** @var modPluginEvent $pluginEvent */
                    $pluginEvent = $this->modx->getObject('modPluginEvent',array(
                        'pluginid' => $this->object->get('id'),
                        'event' => $event['name'],
                    ));
                    if (empty($pluginEvent)) {
                        $pluginEvent = $this->modx->newObject('modPluginEvent');
                    }
                    $pluginEvent->set('pluginid',$this->object->get('id'));
                    $pluginEvent->set('event',$event['name']);
                    $pluginEvent->set('priority',$event['priority']);
                    $pluginEvent->save();
                } else {
                    $pluginEvent = $this->modx->getObject('modPluginEvent',array(
                        'pluginid' => $this->object->get('id'),
                        'event' => $event['name'],
                    ));
                    if (!empty($pluginEvent)) {
                        $pluginEvent->remove();
                    }
                }
            }
        }
    }
}
return 'modPluginCreateProcessor';
