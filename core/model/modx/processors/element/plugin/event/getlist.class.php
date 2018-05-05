<?php
/**
 * Gets a list of system events
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
class modPluginEventGetListProcessor extends modObjectProcessor {
    public $classKey = 'modPluginEvent';
    public $languageTopics = array('plugin','system_events');
    public $permission = 'view_plugin';

    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'name' => false,
            'plugin' => false,
        ));
        return true;
    }

    public function process() {
        $data = $this->getData();

        $list = array();
        /** @var modEvent $event */
        foreach ($data['results'] as $event) {
            $eventArray = $event->toArray();
            $eventArray['enabled'] = $event->get('enabled') ? 1 : 0;

            $eventArray['menu'] = array(
                array(
                    'text' => $this->modx->lexicon('plugin_event_update'),
                    'handler' => 'this.updateEvent',
                )
            );
            $list[] = $eventArray;
        }
        return $this->outputArray($list,$data['total']);
    }

    public function getData() {
        $criteria = array();

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $criteria[] = array('name:LIKE' => '%'.$query.'%');
        }

        $group = $this->getProperty('group');
        if (!empty($group)) {
            $criteria[] = array('groupname' => $group);
        }

        $this->modx->newQuery('modEvent');
        $eventsResult = $this->modx->call('modEvent', 'listEvents', array(
            &$this->modx,
            $this->getProperty('plugin'),
            $criteria,
            array($this->getProperty('sort') => $this->getProperty('dir')),
            $this->getProperty('limit'),
            $this->getProperty('start')
        ));

        return array(
            'total' => $eventsResult['count'],
            'results' => $eventsResult['collection'],
        );
    }
}
return 'modPluginEventGetListProcessor';
