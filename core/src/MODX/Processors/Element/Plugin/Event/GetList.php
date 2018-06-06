<?php

namespace MODX\Processors\Element\Plugin\Event;

use MODX\modEvent;
use MODX\Processors\modObjectProcessor;

/**
 * Gets a list of system events
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
class GetList extends modObjectProcessor
{
    public $classKey = 'modPluginEvent';
    public $languageTopics = ['plugin', 'system_events'];
    public $permission = 'view_plugin';


    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'name' => false,
            'plugin' => false,
        ]);

        return true;
    }


    public function process()
    {
        $data = $this->getData();

        $list = [];
        /** @var modEvent $event */
        foreach ($data['results'] as $event) {
            $eventArray = $event->toArray();
            $eventArray['enabled'] = $event->get('enabled') ? 1 : 0;

            $eventArray['menu'] = [
                [
                    'text' => $this->modx->lexicon('plugin_event_update'),
                    'handler' => 'this.updateEvent',
                ],
            ];
            $list[] = $eventArray;
        }

        return $this->outputArray($list, $data['total']);
    }


    public function getData()
    {
        $criteria = [];

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $criteria[] = ['name:LIKE' => '%' . $query . '%'];
        }

        $group = $this->getProperty('group');
        if (!empty($group)) {
            $criteria[] = ['groupname' => $group];
        }

        $this->modx->newQuery('modEvent');
        $eventsResult = $this->modx->call('modEvent', 'listEvents', [
            &$this->modx,
            $this->getProperty('plugin'),
            $criteria,
            [$this->getProperty('sort') => $this->getProperty('dir')],
            $this->getProperty('limit'),
            $this->getProperty('start'),
        ]);

        return [
            'total' => $eventsResult['count'],
            'results' => $eventsResult['collection'],
        ];
    }
}
