<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource\Event;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Utilities\modFormatter;
use MODX\Revolution\modResource;
use xPDO\Om\xPDOObject;

/**
 * Grabs the site schedule data.
 *
 * @param string $mode pub_date|unpub_date (optional) The mode to grab, either
 * to-publish or to-unpublish. Defaults to pub_date.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 */
class GetList extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_document');
    }

    public function getLanguageTopics()
    {
        return ['resource'];
    }

    public function initialize()
    {
        $this->formatter = new modFormatter($this->modx);
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 10,
            'mode' => 'pub_date',
            'dir' => 'ASC'
        ]);
        return true;
    }

    public function process()
    {
        $data = $this->getData();
        $list = [];
        /** @var modResource $resource */
        foreach ($data['results'] as $resource) {
            if (!$resource->checkPolicy('view')) {
                continue;
            }
            $resourceArray = $this->prepareRow($resource);
            if (!empty($resourceArray)) {
                $list[] = $resourceArray;
            }
        }

        return $this->outputArray($list, $data['total']);
    }

    /**
     * Get the data from a query
     *
     * @return array
     */
    public function getData()
    {
        $data = [];
        $isLimit = $this->getProperty('limit', 10) > 0;

        $c = $this->modx->newQuery(modResource::class);
        $c->where([
            $this->getProperty('mode', 'pub_date') . ':>' => time(),
        ]);
        $data['total'] = $this->modx->getCount(modResource::class, $c);
        $c->sortby($this->getProperty('mode'), $this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($this->getProperty('limit'), $this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection(modResource::class, $c);
        return $data;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();

        unset($objectArray['content']);
        /*
            Note that editor grids will not make use of the empty value; without doing some
            refactoring of the MODx's datetime component, the field definition for this component
            type must specify "date" as its field type and, by doing this, any formatting defined
            below will be lost.
        */
        $pubDate = $object->get('pub_date');
        $objectArray['pub_date'] = in_array($pubDate, $this->managerDateEmptyValues)
            ? $this->managerDateEmptyDisplay
            : $this->formatter->formatManagerDateTime($pubDate, 'combined', true)
            ;

        $unpubDate = $object->get('unpub_date');
        $objectArray['unpub_date'] = in_array($unpubDate, $this->managerDateEmptyValues)
            ? $this->managerDateEmptyDisplay
            : $this->formatter->formatManagerDateTime($unpubDate, 'combined', true)
            ;

        return $objectArray;
    }
}
