<?php
require_once (dirname(dirname(__FILE__)).'/duplicate.class.php');
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
                /** @var modPluginEvent $newEvent */
                $newEvent = $this->modx->newObject('modPluginEvent');
                $newEvent->set('pluginid',$this->newObject->get('id'));
                $newEvent->set('event',$event->get('event'));
                $newEvent->set('priority',$event->get('priority'));
                if ($newEvent->save() == false) {
                    $this->newObject->remove();
                    return $this->failure($this->modx->lexicon('plugin_event_err_duplicate'));
                }
            }
        }
        return $events;
    }
}
return 'modPluginDuplicateProcessor';