<?php
/**
 * Remove a system even
 *
 * @param string $name The nameof the event
 *
 * @package modx
 * @subpackage processors.system.events
 */
class modSystemEventsRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modEvent';
    public $languageTopics = array('events');
    public $permission = 'events';
    public $objectType = 'event';
    public $primaryKeyField = 'name';
	
	public function beforeRemove() {
		$service = $this->object->get('service');
		if ($service != 6) {
			return $this->modx->lexicon('system_events_err_remove_not_allowed');
		}
		return parent::beforeRemove();
	}
}
return 'modSystemEventsRemoveProcessor';