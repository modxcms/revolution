<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;

use MODX\Revolution\Processors\Model\GetProcessor;
use MODX\Revolution\modResource;

/**
 * Retrieves a resource by its ID.
 *
 * @param integer $id The ID of the resource to grab
 * @return modResource
 */
class Get extends GetProcessor
{
    public $classKey = modResource::class;
    public $languageTopics = ['resource'];
    public $objectType = 'resource';
    public $permission = 'view';

    public function process()
    {
        $resourceArray = $this->object->toArray();
        $resourceArray['canpublish'] = $this->modx->hasPermission('publish_document');
        if (!$this->getProperty('skipFormatDates') ||
            ($this->getProperty('skipFormatDates') && $this->getProperty('skipFormatDates') == 'false')) {
            $this->formatDates($resourceArray);
        }
        return $this->success('', $resourceArray);
    }

    public function formatDates(array &$resourceArray)
    {
        $format = $this->modx->getOption('manager_date_format') . ' ' . $this->modx->getOption('manager_time_format');

        if (!empty($resourceArray['pub_date']) && $resourceArray['pub_date'] != '0000-00-00 00:00:00') {
            $resourceArray['pub_date'] = date($format, strtotime($resourceArray['pub_date']));
        } else {
            $resourceArray['pub_date'] = '';
        }
        if (!empty($resourceArray['unpub_date']) && $resourceArray['unpub_date'] != '0000-00-00 00:00:00') {
            $resourceArray['unpub_date'] = date($format, strtotime($resourceArray['unpub_date']));
        } else {
            $resourceArray['unpub_date'] = '';
        }
        if (!empty($resourceArray) && $resourceArray['publishedon'] != '0000-00-00 00:00:00') {
            $resourceArray['publishedon'] = date($format, strtotime($resourceArray['publishedon']));
        } else {
            $resourceArray['publishedon'] = '';
        }
    }
}
