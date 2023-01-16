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

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modSystemSetting;
use PDO;
use xPDO\Om\xPDOQuery;

/**
 * Get a list of setting areas
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\Settings
 */
class GetAreas extends Processor
{
    public $permission = 'settings';

    /**
     * @return mixed
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission($this->permission);
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['setting', 'namespace'];
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'dir' => 'ASC',
            'namespace' => 'core',
        ]);
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $c = $this->getQuery();
        if ($c === null) {
            return $this->failure();
        }

        $list = [];
        if ($c->prepare() && $c->stmt->execute()) {
            $optionData = [];
            while ($r = $c->stmt->fetch(PDO::FETCH_NUM)) {
                list($area, $namespace, $count) = $r;
                $name = $area;
                if ($namespace !== 'core') {
                    $this->modx->lexicon->load($namespace . ':default', $namespace . ':setting');
                }
                $lex = 'area_' . $name;
                if ($this->modx->lexicon->exists($lex)) {
                    $name = $this->modx->lexicon($lex);
                }
                if (empty($name)) {
                    $name = $this->modx->lexicon('none');
                }
                if (array_key_exists($area, $optionData)) {
                    $optionData[$area]['count'] = $count + $optionData[$area]['count'];
                } else {
                    $optionData[$area]['label'] = $name;
                    $optionData[$area]['count'] = $count;
                }
            }
        }
        if (!empty($optionData)) {
            foreach ($optionData as $area => $areaData) {
                $list[] = [
                    'd' => $areaData['label'] . ' (' . $areaData['count'] . ')',
                    'v' => $area
                ];
            }
            usort($list, function ($a, $b) {
                return strtolower($a['d']) <=> strtolower($b['d']);
            });
        }
        return $this->outputArray($list);
    }

    /**
     * Get the query object for the data
     * @return xPDOQuery
     */
    public function getQuery()
    {
        $namespace = $this->getProperty('namespace', 'core');
        $query = $this->getProperty('query');

        $c = $this->modx->newQuery(modSystemSetting::class);
        $c->setClassAlias('settingsArea');
        $c->leftJoin(modSystemSetting::class, 'settingsCount', [
            'settingsArea.' . $this->modx->escape('key') . ' = settingsCount.' . $this->modx->escape('key'),
        ]);
        if (!empty($namespace)) {
            $c->where([
                'settingsArea.namespace' => $namespace,
            ]);
        }
        if (!empty($query)) {
            $c->where([
                'settingsArea.area:LIKE' => "%{$query}%",
            ]);
        }
        $c->select([
            'settingsArea.' . $this->modx->escape('area'),
            'settingsArea.' . $this->modx->escape('namespace'),
            'COUNT(settingsCount.' . $this->modx->escape('key') . ') AS num_settings',
        ]);
        $c->groupby('settingsArea.' . $this->modx->escape('area') . ', settingsArea.' . $this->modx->escape('namespace'));
        $c->sortby($this->modx->escape('area'), $this->getProperty('dir', 'ASC'));
        return $c;
    }
}
