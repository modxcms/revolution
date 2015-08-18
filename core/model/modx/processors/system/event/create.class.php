<?php
/**
 * Create a system event
 *
 * @package modx
 * @subpackage processors.system.events
 */
class modSystemEventsCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modEvent';
    public $languageTopics = array('events');
    public $permission = 'events';
    public $objectType = 'event';
    public $primaryKeyField = 'name';

    public function beforeSave() {
       
        /* prevent empty or already existing settings */
        $name = trim($this->getProperty('name'));
        if (empty($name)) $this->addFieldError('name',$this->modx->lexicon('system_events_err_ns'));
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name',$this->modx->lexicon('system_events_err_ae'));
        }

        /* prevent keys starting with numbers */
        $numbers = explode(',','1,2,3,4,5,6,7,8,9,0');
        if (in_array(substr($name,0,1),$numbers)) {
            $this->addFieldError('name',$this->modx->lexicon('system_events_err_startint'));
        }
        $this->object->set('name',$name);

        return !$this->hasErrors();

    }

    /**
     * Check to see if a Event already exists with this name
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('modEvent',array(
            'name' => $name,
        )) > 0;
    }
}
return 'modSystemEventsCreateProcessor';