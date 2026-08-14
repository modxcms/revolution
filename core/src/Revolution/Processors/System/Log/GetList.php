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

use MODX\Revolution\Formatter\modManagerDateFormatter;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modCategory;
use MODX\Revolution\modChunk;
use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\modContentType;
use MODX\Revolution\modDashboard;
use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\modDocument;
use MODX\Revolution\modManagerLog;
use MODX\Revolution\modMenu;
use MODX\Revolution\modNamespace;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modResource;
use MODX\Revolution\modResourceGroup;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modStaticResource;
use MODX\Revolution\modSymLink;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserSetting;
use MODX\Revolution\modWebLink;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Sources\modMediaSource;
use MODX\Revolution\Transport\modTransportPackage;
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
    private modManagerDateFormatter $formatter;

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
        $this->formatter = $this->modx->services->get(modManagerDateFormatter::class);
        $this->setDefaultProperties([
            'limit' => 20,
            'start' => 0,
            'sort' => 'occurred',
            'dir' => 'DESC',
            'user' => false,
            'actionType' => false,
            'dateStart' => false,
            'dateEnd' => false,
            'dateFormat' => '',
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
            $nsCorePath = $this->modx->getOption('core_path') . "components/{$ns}/";
            $path = $this->modx->getOption("{$ns}.core_path", null, $nsCorePath) . 'model/';
            $this->modx->addPackage($ns, $path);
        }
        $obj = null;
        if (!empty($logArray['classKey']) && !empty($logArray['item'])) {
            $logArray['name'] = $logArray['classKey'] . ' (' . $logArray['item'] . ')';
            /** @var xPDOObject|null $obj */
            $obj = $this->modx->getObject($logArray['classKey'], $logArray['item']);
            /* item is varchar; object PKs are often int — compare as strings */
            if ($obj && (string) $obj->get($obj->getPK()) === (string) $logArray['item']) {
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

        $managerUrl = $this->getManagerUrl(
            $logArray['classKey'] ?? '',
            $logArray['item'] ?? '',
            $obj
        );
        $logArray['managerUrl'] = $managerUrl;

        $customFormat = $this->getProperty('dateFormat');
        $logArray['occurred'] = !empty($customFormat)
            ? $this->formatter->format($logArray['occurred'], $customFormat)
            : $this->formatter->formatDateTime($logArray['occurred'])
            ;

        return $logArray;
    }

    /**
     * Build manager URL for a log entry so the object can be opened in the manager.
     *
     * @param string $classKey Class key of the logged object
     * @param string $item Primary key value stored in the log
     * @param xPDOObject|null $obj Loaded object when available (used for context key, menu text, etc.)
     * @return string|null Relative URL (e.g. ?a=resource/update&id=1) or null when no link is supported
     */
    protected function getManagerUrl(string $classKey, string $item, ?xPDOObject $obj = null): ?string
    {
        if (empty($classKey) || $item === '' || $item === 'unknown') {
            return null;
        }

        $action = null;
        $paramKey = 'id';
        $paramValue = $item;

        switch ($classKey) {
            case modResource::class:
            case modWebLink::class:
            case modSymLink::class:
            case modStaticResource::class:
            case modDocument::class:
                $action = 'resource/update';
                break;
            case modContext::class:
                $action = 'context/update';
                $paramKey = 'key';
                $paramValue = ($obj !== null) ? (string) $obj->get('key') : $item;
                if ($paramValue === '') {
                    return null;
                }
                break;
            case modTemplate::class:
                $action = 'element/template/update';
                break;
            case modTemplateVar::class:
                $action = 'element/tv/update';
                break;
            case modChunk::class:
                $action = 'element/chunk/update';
                break;
            case modSnippet::class:
                $action = 'element/snippet/update';
                break;
            case modPlugin::class:
                $action = 'element/plugin/update';
                break;
            case modCategory::class:
                $action = 'element/category/update';
                break;
            case modUser::class:
                $action = 'security/user/update';
                break;
            case modMenu::class:
                $action = 'element/menu/update';
                $paramKey = 'text';
                $paramValue = ($obj !== null) ? (string) $obj->get('text') : $item;
                if ($paramValue === '') {
                    return null;
                }
                break;
            case modSystemSetting::class:
                return null;
            case modContextSetting::class:
                $action = 'context/update';
                $paramKey = 'key';
                $paramValue = ($obj !== null) ? (string) $obj->get('context_key') : $item;
                if ($paramValue === '') {
                    return null;
                }
                break;
            case modUserSetting::class:
                $action = 'security/user/update';
                $paramValue = ($obj !== null) ? (string) $obj->get('user') : $item;
                break;
            case modAccessPolicy::class:
                $action = 'security/access/policy/update';
                break;
            case modAccessPolicyTemplate::class:
                $action = 'security/access/policy/template/update';
                break;
            case modResourceGroup::class:
                return null;
            case modMediaSource::class:
                $action = 'source/update';
                break;
            case modNamespace::class:
                $action = 'workspaces/namespace';
                $paramKey = 'name';
                $paramValue = ($obj !== null) ? (string) $obj->get('name') : $item;
                if ($paramValue === '') {
                    return null;
                }
                break;
            case modDashboardWidget::class:
                $action = 'system/dashboards/widget/update';
                break;
            case modDashboard::class:
                $action = 'system/dashboards/update';
                break;
            case modTransportPackage::class:
                $action = 'workspaces/package/view';
                $paramKey = 'signature';
                $paramValue = ($obj !== null) ? (string) $obj->get('signature') : $item;
                if ($paramValue === '') {
                    return null;
                }
                break;
            case modContentType::class:
                $action = 'system/contenttype/update';
                break;
            default:
                return null;
        }

        $params = [$paramKey => $paramValue];

        return '?a=' . $action . '&' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
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
            // no default
        }
        return $field;
    }
}
