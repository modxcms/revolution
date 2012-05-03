<?php
require_once (dirname(dirname(__FILE__)).'/update.class.php');
/**
 * Update a plugin.
 *
 * @param integer $id The ID of the plugin.
 * @param string $name The name of the plugin.
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
class modPluginUpdateProcessor extends modElementUpdateProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin','category','element');
    public $permission = 'save_plugin';
    public $objectType = 'plugin';
    public $beforeSaveEvent = 'OnBeforePluginFormSave';
    public $afterSaveEvent = 'OnPluginFormSave';

    public function beforeSave() {
        $disabled = (boolean)$this->getProperty('disabled',false);
        $this->object->set('disabled',$disabled);

        return parent::beforeSave();
    }

    public function afterSave() {
        $this->setSystemEvents();
        return parent::afterSave();
    }

    /**
     * Update system event associations
     * @return void
     */
    public function setSystemEvents() {
        $events = $this->getProperty('events',null);
        if ($events !== null) {
            $pluginEvents = is_array($events) ? $events : $this->modx->fromJSON($events);
            foreach ($pluginEvents as $id => $event) {
                /** @var modPluginEvent $pluginEvent */
                $pluginEvent = $this->modx->getObject('modPluginEvent',array(
                    'pluginid' => $this->object->get('id'),
                    'event' => $event['name'],
                ));
                if ($event['enabled']) {
                    if ($pluginEvent) { /* for some reason existing plugin events need to be removed before the propertyset field can be edited. doing so here. */
                        $pluginEvent->remove();
                    }
                    $pluginEvent = $this->modx->newObject('modPluginEvent');
                    $pluginEvent->set('pluginid',$this->object->get('id'));
                    $pluginEvent->set('event',$event['name']);
                    $pluginEvent->set('priority',(int)$event['priority']);
                    $pluginEvent->set('propertyset',(int)$event['propertyset']);
                    if (!$pluginEvent->save()) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not save plugin event: '.print_r($pluginEvent->toArray(),true));
                    }
                } elseif ($pluginEvent) {
                    $pluginEvent->remove();
                }
            }
        }
    }

    public function cleanup() {
        return $this->success('',array_merge($this->object->get(array('id', 'name', 'description', 'locked', 'category', 'disabled', 'plugincode')), array('previous_category' => $this->previousCategory)));
    }
}
return 'modPluginUpdateProcessor';
