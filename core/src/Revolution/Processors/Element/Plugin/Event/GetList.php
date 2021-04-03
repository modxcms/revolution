<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Plugin\Event;


use MODX\Revolution\modEvent;
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modPluginEvent;

/**
 * Gets a list of system events
 *
 * @package MODX\Revolution\Processors\Element\Plugin\Event
 */
class GetList extends ModelProcessor
{
    public $classKey = modPluginEvent::class;
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
                    'text' => $this->modx->lexicon('edit'),
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

        $this->modx->newQuery(modEvent::class);
        $eventsResult = $this->modx->call(modEvent::class, 'listEvents', [
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
