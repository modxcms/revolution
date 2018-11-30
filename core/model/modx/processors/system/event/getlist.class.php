<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Gets a list of system events
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.event
 */
class modSystemEventGetListProcessor extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('events');
    }

    public function getLanguageTopics()
    {
        return array('events');
    }

    public function initialize()
    {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'modEvent_groupname ASC, modEvent_name',
            'dir' => 'ASC',
        ));
        return true;
    }

    public function process()
    {
        $data = $this->getData();

        $list = array();
        /** @var modEvent $event */
        foreach ($data['results'] as $event) {
            $eventArray = $event->toArray();

            if ($eventArray['plugins']) {
                $c = $this->modx->newQuery('modPlugin');
                $c->leftJoin('modPluginEvent', 'modPluginEvent', 'modPluginEvent.pluginid = modPlugin.id');
                $c->sortby('modPluginEvent.priority', 'ASC');
                $c->where(array('modPluginEvent.event' => $eventArray['name']));

                $plugins = array();
                foreach ($this->modx->getIterator('modPlugin', $c) as $plugin) {
                    $plugin = $plugin->toArray();

                    $pluginParams = array();
                    if ($plugin['disabled']) { $pluginParams[] = $this->modx->lexicon('disabled'); }
                    if ($plugin['locked']) { $pluginParams[] = $this->modx->lexicon('locked'); }

                    $pluginName = count($pluginParams)
                        ? sprintf('%s (%s)', $plugin['name'], join(', ', $pluginParams))
                        : $plugin['name'];

                    $plugins[] = array('name' => $pluginName);
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
        $data = array();

        $c = $this->modx->newQuery('modEvent');
        $c->select('modEvent.*');
        $c->select(['modEvent_plugins' => 'count(modPluginEvent.pluginid)']);
        $c->leftJoin('modPluginEvent', 'modPluginEvent', 'modPluginEvent.event = modEvent.name');
        $c->groupby('modEvent.name');

        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%' . $query . '%',
            ));
        }

        $id = $this->getProperty('id', 0);
        if (!empty($id)) {
            $c->where(array(
                'modEvent.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ));
        }

        $data['total'] = $this->modx->getCount('modEvent', $c);
        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($limit, $this->getProperty('start'));
        }

        $data['results'] = $this->modx->getCollection('modEvent', $c);

        return $data;
    }
}

return 'modSystemEventGetListProcessor';
