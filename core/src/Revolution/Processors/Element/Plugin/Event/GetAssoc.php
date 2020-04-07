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


use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modPluginEvent;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of plugins associated to system event
 *
 * @package MODX\Revolution\Processors\Element\Plugin\Event
 */
class GetAssoc extends GetListProcessor
{
    public $classKey = modPlugin::class;
    public $languageTopics = ['plugin'];
    public $permission = 'view_plugin';
    public $objectType = 'plugin';

    /**
     * Filter by system event
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin(modPluginEvent::class, 'modPluginEvent', [
            'modPluginEvent.pluginid = modPlugin.id',
            'modPluginEvent.event' => $this->getProperty('event'),
        ]);

        return $c;
    }

    /**
     * Add selection of priority and propertyset
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns(modPlugin::class, 'modPlugin'));
        $c->select([
            'modPluginEvent.priority',
            'modPluginEvent.propertyset',
        ]);

        return $c;
    }

    /**
     * Filter only desired fields
     *
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray = [
            $objectArray['id'],
            $objectArray['name'],
            $objectArray['priority'],
            $objectArray['propertyset'],
        ];

        return $objectArray;
    }
}
