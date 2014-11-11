<?php
/**
 * Associate the event to the plugins.
 *
 * @param string $name The name of the event.
 * @param string $plugins JSON string of the form [{"id":1,"name":"PluginName","priority":"0","propertyset":"0","menu":null},{...},...].
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
class modPluginEventAssociateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modEvent';
    public $primaryKeyField = 'name';
    public $languageTopics = array('plugin','system_events');
    public $permission = 'save_plugin';
    public $objectType = 'plugin_event';
    public $checkSavePermission = true;

    public function beforeSave() {
        /* get plugins */
        $plugins = $this->modx->fromJSON($this->getProperty('plugins'));

        $eventName = $this->object->get('name');

        $this->modx->removeCollection('modPluginEvent', array(
            'event' => $eventName,
        ));

        foreach ($plugins as $pluginArray) {
            if (empty($pluginArray['id'])) {
                continue;
            }

            $pluginEvent = $this->modx->newObject('modPluginEvent');
            $pluginEvent->set('event',$eventName);
            $pluginEvent->set('pluginid',$pluginArray['id']);
            $priority = (!empty($pluginArray['priority']) ? $pluginArray['priority'] : 0);
            $pluginEvent->set('priority',(int)$priority);
            $pluginEvent->set('propertyset',(int)(!empty($pluginArray['propertyset']) ? $pluginArray['propertyset'] : 0));

            if (!$pluginEvent->save()) {
                $this->addFieldError('plugin_event_err_save', $this->modx->lexicon('plugin_event_err_save').print_r($pluginEvent->toArray(),true));
            }
        }

        return parent::beforeSave();
    }

    /**
     * Return the success message
     * @return array
     */
    public function cleanup() {
        return $this->success();
    }
}
return 'modPluginEventAssociateProcessor';