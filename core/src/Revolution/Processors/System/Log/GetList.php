<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Log;

use MODX\Revolution\modCategory;
use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\modDocument;
use MODX\Revolution\modMenu;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modManagerLog;
use MODX\Revolution\modResource;
use MODX\Revolution\modStaticResource;
use MODX\Revolution\modSymLink;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserSetting;
use MODX\Revolution\modWebLink;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of manager log actions
 * @param string $actionType (optional) If set, will filter by action type
 * @param integer $user (optional) If set, will filter by user
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to occurred.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\Log
 */
class GetList extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('mgr_log_view');
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'limit' => 20,
            'start' => 0,
            'sort' => 'occurred',
            'dir' => 'DESC',
            'user' => false,
            'actionType' => false,
            'dateStart' => false,
            'dateEnd' => false,
            'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format'),
        ]);
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $data = $this->getData();

        $list = [];
        /** @var modManagerLog $log */
        foreach ($data['results'] as $log) {
            $logArray = $this->prepareLog($log);
            if (!empty($logArray)) {
                $list[] = $logArray;
            }
        }

        return $this->outputArray($list, $data['total']);
    }

    /**
     * Get a collection of modManagerLog objects
     * @return array
     */
    public function getData()
    {
        $actionType = $this->getProperty('actionType');
        $classKey = $this->explodeAndClean($this->getProperty('classKey'));
        $item = $this->getProperty('item');
        $user = $this->getProperty('user');
        $dateStart = $this->getProperty('dateStart');
        $dateEnd = $this->getProperty('dateEnd');
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);
        $data = [];

        /* check filters */
        $wa = [];
        if (!empty($actionType)) {
            $wa['action:LIKE'] = '%' . $actionType . '%';
        }
        if (!empty($classKey)) {
            $classQuery = [];
            foreach ($classKey as $c) {
                $classQuery[] = ['OR:classKey:LIKE' => '%' . $c . '%'];
            }
            $wa[] = $classQuery;
        }
        if (!empty($item)) {
            $wa['item:LIKE'] = '%' . $item . '%';
        }
        if (!empty($user)) {
            $wa['user'] = $user;
        }
        if (!empty($dateStart)) {
            $dateStart = date('Y-m-d', strtotime($dateStart . ' 00:00:00'));
            $wa['occurred:>='] = $dateStart;
        }
        if (!empty($dateEnd)) {
            $dateEnd = date('Y-m-d', strtotime($dateEnd . ' 23:59:59'));
            $wa['occurred:<='] = $dateEnd;
        }

        /* build query */
        $c = $this->modx->newQuery(modManagerLog::class);
        $c->innerJoin(modUser::class, 'User');
        if (!empty($wa)) {
            $c->where($wa);
        }
        $data['total'] = $this->modx->getCount(modManagerLog::class, $c);

        $c->select($this->modx->getSelectColumns(modManagerLog::class, 'modManagerLog'));
        $c->select($this->modx->getSelectColumns(modUser::class, 'User', '', ['username']));
        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        $c->sortby('occurred', 'DESC');
        if ($isLimit) {
            $c->limit($limit, $this->getProperty('start'));
        }
        $data['results'] = $this->modx->getIterator(modManagerLog::class, $c);

        return $data;
    }

    /**
     * Convert comma separated field into array and clean up
     * @param string $string field to be processed
     * @param string $delimiter the value to explode defaults to ','
     * @param boolean $keepZero remove empty values from the array
     * @return array
     */
    public function explodeAndClean($string, $delimiter = ',', $keepZero = false)
    {
        $array = explode($delimiter, $string);            // Explode fields to array
        $array = array_map('trim', $array);       // Trim array's values
        $array = array_keys(array_flip($array));  // Remove duplicate fields

        if ($keepZero === false) {
            $array = array_filter($array);            // Remove empty values from array
        } else {
            $array = array_filter($array, function ($value) {
                return $value !== '';
            });
        }

        return $array;
    }

    /**
     * Prepare a log entry for listing
     * @param modManagerLog $log
     * @return array
     */
    public function prepareLog(modManagerLog $log)
    {
        $logArray = $log->toArray();
        if (strpos($logArray['action'], '.') !== false) {
            // Action is prefixed with a namespace, assume we need to load a package
            $exp = explode('.', $logArray['action']);
            $ns = $exp[0];
            $path = $this->modx->getOption("{$ns}.core_path", null,
                    $this->modx->getOption('core_path') . "components/{$ns}/") . 'model/';
            $this->modx->addPackage($ns, $path);
        }
        if (!empty($logArray['classKey']) && !empty($logArray['item'])) {
            $logArray['name'] = $logArray['classKey'] . ' (' . $logArray['item'] . ')';
            /** @var xPDOObject $obj */
            $obj = $this->modx->getObject($logArray['classKey'], $logArray['item']);
            if ($obj && ($obj->get($obj->getPK()) === $logArray['item'])) {
                $nameField = $this->getNameField($logArray['classKey']);
                $k = $obj->getField($nameField, true);
                if (!empty($k)) {
                    $pk = $obj->get('id');
                    $logArray['name'] = $obj->get($nameField) . (!empty($pk) ? ' (' . $pk . ')' : '');
                }
            }
        } else {
            $logArray['name'] = $log->get('item');
        }
        $logArray['occurred'] = date($this->getProperty('dateFormat'), strtotime($logArray['occurred']));

        return $logArray;
    }

    /**
     * Get the name field of the class
     * @param string $classKey
     * @return string
     */
    public function getNameField($classKey)
    {
        $field = 'name';
        switch ($classKey) {
            case modResource::class:
            case modWebLink::class:
            case modSymLink::class:
            case modStaticResource::class:
            case modDocument::class:
                $field = 'pagetitle';
                break;
            case modCategory::class:
                $field = 'category';
                break;
            case modContext::class:
                $field = 'key';
                break;
            case modTemplate::class:
                $field = 'templatename';
                break;
            case modUser::class:
                $field = 'username';
                break;
            case modMenu::class:
                $field = 'text';
                break;
            case modSystemSetting::class:
            case modContextSetting::class:
            case modUserSetting::class:
                $field = 'key';
                break;
            default:
                break;
        }
        return $field;
    }
}
