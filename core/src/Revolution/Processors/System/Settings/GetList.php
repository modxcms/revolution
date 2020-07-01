<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Settings;

use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modSystemSetting;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Get a list of system settings
 * @param string $key (optional) If set, will search by this value
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package modx
 * @subpackage processors.system.settings
 */
class GetList extends GetListProcessor
{
    public $classKey = modSystemSetting::class;
    public $languageTopics = ['setting', 'namespace'];
    public $permission = 'settings';
    public $defaultSortField = 'key';

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'key' => false,
            'namespace' => false,
            'area' => false,
            'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format'),
        ]);

        return $initialized;
    }

    public function prepareCriteria()
    {
        return [];
    }

    /**
     * Get a collection of modSystemSetting objects
     * @return array
     */
    public function getData()
    {
        $query = $this->getProperty('query', false);
        $data = [];

        /** @var xPDOQuery $criteria */
        $criteria = $this->prepareCriteria();
        if (!empty($query)) {
            $criteria[] = [
                $this->modx->getAlias($this->classKey) . '.key:LIKE' => '%' . $query . '%',
                'OR:Entry.value:LIKE' => '%' . $query . '%',
                'OR:' . $this->modx->getAlias($this->classKey) . '.value:LIKE' => '%' . $query . '%',
                'OR:Description.value:LIKE' => '%' . $query . '%',
            ];
        }

        $namespace = $this->getProperty('namespace', false);
        if (!empty($namespace)) {
            /** @var modNamespace $namespaceObject */
            $namespaceObject = $this->modx->getObject(modNamespace::class, $namespace);
            if (!$namespaceObject) {
                $criteria[] = ['1 != 1'];
            }
            $criteria[] = ['namespace' => $namespace];
        }

        $area = $this->getProperty('area', false);
        if (!empty($area)) {
            $criteria[] = ['area' => $area];
        }

        $settingsResult = $this->modx->call($this->classKey, 'listSettings', [
            &$this->modx,
            $criteria,
            [$this->getProperty('sort') => $this->getProperty('dir')],
            $this->getProperty('limit'),
            $this->getProperty('start'),
        ]);
        $data['total'] = $settingsResult['count'];
        $data['results'] = $settingsResult['collection'];

        return $data;
    }

    /**
     * Prepare a setting for output
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $settingArray = $object->toArray();
        $k = 'setting_' . $settingArray['key'];

        /* if 3rd party setting, load proper text, fallback to english */
        $this->modx->lexicon->load('en:' . $object->get('namespace') . ':default',
            'en:' . $object->get('namespace') . ':setting');
        $this->modx->lexicon->load($object->get('namespace') . ':default', $object->get('namespace') . ':setting');

        /* get translated area text */
        if ($this->modx->lexicon->exists('area_' . $object->get('area'))) {
            $settingArray['area_text'] = $this->modx->lexicon('area_' . $object->get('area'));
        } else {
            $settingArray['area_text'] = $settingArray['area'];
        }

        /* get translated name and description text */
        if (empty($settingArray['description_trans'])) {
            if ($this->modx->lexicon->exists($k . '_desc')) {
                $settingArray['description_trans'] = $this->modx->lexicon($k . '_desc');
                $settingArray['description'] = $k . '_desc';
            } else {
                $settingArray['description_trans'] = !empty($settingArray['description']) ? $settingArray['description'] : '';
            }
        } else {
            $settingArray['description'] = $settingArray['description_trans'];
        }
        if (empty($settingArray['name_trans'])) {
            if ($this->modx->lexicon->exists($k)) {
                $settingArray['name_trans'] = $this->modx->lexicon($k);
                $settingArray['name'] = $k;
            } else {
                $settingArray['name_trans'] = $settingArray['key'];
            }
        } else {
            $settingArray['name'] = $settingArray['name_trans'];
        }
        $settingArray['key'] = htmlspecialchars($settingArray['key'], ENT_QUOTES,
            $this->modx->getOption('modx_charset', null, 'UTF-8'));
        $settingArray['name_trans'] = htmlspecialchars($settingArray['name_trans'], ENT_QUOTES,
            $this->modx->getOption('modx_charset', null, 'UTF-8'));

        $settingArray['oldkey'] = $settingArray['key'];

        $settingArray['editedon'] = in_array($object->get('editedon'), [
                '-001-11-30 00:00:00',
                '-1-11-30 00:00:00',
                '0000-00-00 00:00:00',
                null
            ]) ? '' : date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));

        return $settingArray;
    }
}
