<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Event;

use MODX\Revolution\modEvent;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modPluginEvent;
use MODX\Revolution\Processors\Processor;

/**
 * Gets a list of system events
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\Event
 */
class GetList extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('events');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['events'];
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 10,
            'sort' => 'modEvent_groupname ASC, modEvent_name',
            'dir' => 'ASC',
        ]);
        return true;
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        $data = $this->getData();

        $list = [];
        /** @var modEvent $event */
        foreach ($data['results'] as $event) {
            $eventArray = $event->toArray();

            if ($eventArray['plugins']) {
                $c = $this->modx->newQuery(modPlugin::class);
                $c->leftJoin(modPluginEvent::class, 'modPluginEvent', 'modPluginEvent.pluginid = modPlugin.id');
                $c->sortby('modPluginEvent.priority');
                $c->where(['modPluginEvent.event' => $eventArray['name']]);

                $plugins = [];
                foreach ($this->modx->getIterator(modPlugin::class, $c) as $plugin) {
                    $plugin = $plugin->toArray();

                    $pluginParams = [];
                    if ($plugin['disabled']) {
                        $pluginParams[] = $this->modx->lexicon('disabled');
                    }
                    if ($plugin['locked']) {
                        $pluginParams[] = $this->modx->lexicon('locked');
                    }

                    $pluginName = count($pluginParams) ? sprintf('%s (%s)', $plugin['name'],
                        implode(', ', $pluginParams)) : $plugin['name'];

                    $plugins[] = ['name' => $pluginName];
                }
                $eventArray['plugins'] = $plugins;
            } else {
                $eventArray['plugins'] = '';
            }

            $list[] = $eventArray;
        }

        return $this->outputArray($list, $data['total']);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);
        $data = [];

        $c = $this->modx->newQuery(modEvent::class);
        $c->select('modEvent.*');
        $c->select(['modEvent_plugins' => 'count(modPluginEvent.pluginid)']);
        $c->leftJoin(modPluginEvent::class, 'modPluginEvent', 'modPluginEvent.event = modEvent.name');
        $c->groupby('modEvent.name');

        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'name:LIKE' => '%' . $query . '%',
            ]);
        }

        $id = $this->getProperty('id', 0);
        if (!empty($id)) {
            $c->where([
                'modEvent.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        $data['total'] = $this->modx->getCount(modEvent::class, $c);
        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($limit, $this->getProperty('start'));
        }

        $data['results'] = $this->modx->getCollection(modEvent::class, $c);

        return $data;
    }
}
