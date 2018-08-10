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
 * Grabs the site schedule data.
 *
 * @param string $mode pub_date|unpub_date (optional) The mode to grab, either
 * to-publish or to-unpublish. Defaults to pub_date.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.resource.event
 */
class modResourceEventGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_document');
    }
    public function getLanguageTopics() {
        return array('resource');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'mode' => 'pub_date',
            'dir' => 'ASC',
            'timeFormat' => '%a %b %d, %Y %H:%M',
            'offset' => floatval($this->modx->getOption('server_offset_time',null,0)) * 3600,
        ));
        return true;
    }
    public function process() {
        $data = $this->getData();
        $list = array();
        /** @var modResource $resource */
        foreach ($data['results'] as $resource) {
            if (!$resource->checkPolicy('view')) continue;
            $resourceArray = $this->prepareRow($resource);
            if (!empty($resourceArray)) {
                $list[] = $resourceArray;
            }
        }

        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get the data from a query
     *
     * @return array
     */
    public function getData() {
        $data = array();
        $isLimit = $this->getProperty('limit',10) > 0;

        $c = $this->modx->newQuery('modResource');
        $c->where(array(
            $this->getProperty('mode','pub_date').':>' => time(),
        ));
        $data['total'] = $this->modx->getCount('modResource',$c);
        $c->sortby($this->getProperty('mode'),$this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($this->getProperty('limit'),$this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection('modResource',$c);
        return $data;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $timeFormat = $this->getProperty('timeFormat','%a %b %d, %Y');
        $offset = $this->getProperty('offset',0);

        $objectArray = $object->toArray();
        unset($objectArray['content']);

        if (!in_array($object->get('pub_date'),array('','1969-12-31 00:00:00'))) {
            $pubDate = strtotime($object->get('pub_date'))+$offset;
            $objectArray['pub_date'] = strftime($timeFormat,$pubDate);
        } else {
            $objectArray['pub_date'] = '';
        }

        if (!in_array($object->get('unpub_date'),array('','1969-12-31 00:00:00'))) {
            $unpubDate = strtotime($object->get('unpub_date'))+$offset;
            $objectArray['unpub_date'] = strftime($timeFormat,$unpubDate);
        } else {
            $objectArray['unpub_date'] = '';
        }
        return $objectArray;
    }
}
return 'modResourceEventGetListProcessor';
