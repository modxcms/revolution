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
use MODX\Revolution\modContextSetting;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modUserGroupSetting;
use MODX\Revolution\modUserSetting;
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

    /** @param boolean $isGridFilter Indicates the target of this list data is a filter field */
    protected $isGridFilter = false;

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
            'query' => ''
        ]);
        $this->isGridFilter = $this->getProperty('isGridFilter', false);
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
        $alias = 'settingsArea';
        $aliasEscaped = $this->modx->escape($alias);
        $settingsClass = modSystemSetting::class;

        // $foreignKey is the primary key of the child settings entity (e.g., user, usergroup, context)
        $foreignKey = $this->getProperty('foreignKey', '');
        $foreignKeyWhere = null;

        /*
            When this class is used to fetch data for a grid filter's store (combo),
            limit results to only those areas present in the current grid.
        */
        if ($this->isGridFilter && $this->getProperty('targetGrid', false) === 'MODx.grid.SettingsGrid') {
            $settingsType = $this->getProperty('targetSettingsType', 'system');
            switch ($settingsType) {
                case 'context':
                    $settingsClass = modContextSetting::class;
                    $foreignKeyWhere = $foreignKey
                        ? [ $aliasEscaped . '.' . $this->modx->escape('context_key') => $this->modx->sanitizeString($foreignKey) ]
                        : null
                        ;
                    break;
                case 'group':
                    $settingsClass = modUserGroupSetting::class;
                    $foreignKeyWhere = $foreignKey
                        ? [ $aliasEscaped . '.' . $this->modx->escape('group') => (int)$foreignKey ]
                        : null
                        ;
                    break;
                case 'user':
                    $settingsClass = modUserSetting::class;
                    $foreignKeyWhere = $foreignKey
                        ? [ $aliasEscaped . '.' . $this->modx->escape('user') => (int)$foreignKey ]
                        : null
                        ;
                    break;
                // no default
            }
        }
        $areaColumn = $this->modx->escape('area');
        $keyColumn = $this->modx->escape('key');
        $namespaceColumn = $this->modx->escape('namespace');
        $joinAlias = 'settingsCount';
        $joinAliasEscaped = $this->modx->escape($joinAlias);

        $c = $this->modx->newQuery($settingsClass);
        $c->setClassAlias($alias);
        $c->leftJoin($settingsClass, $joinAlias, [
            "{$aliasEscaped}.{$keyColumn} = {$joinAliasEscaped}.{$keyColumn}"
        ]);
        if ($namespace = $this->getProperty('namespace', false)) {
            $c->where([
                "{$aliasEscaped}.{$namespaceColumn}" => $namespace
            ]);
        }
        if ($query = $this->getProperty('query', '')) {
            $c->where([
                "{$aliasEscaped}.{$areaColumn}:LIKE" => "%{$query}%"
            ]);
        }
        if ($foreignKeyWhere) {
            $c->where($foreignKeyWhere);
        }
        $c->select([
            "{$aliasEscaped}.{$areaColumn}",
            "{$aliasEscaped}.{$namespaceColumn}",
            "COUNT({$joinAliasEscaped}.{$keyColumn}) AS num_settings",
        ]);
        $c->groupby("{$aliasEscaped}.{$areaColumn}, {$aliasEscaped}.{$namespaceColumn}");
        $c->sortby($areaColumn, $this->getProperty('dir', 'ASC'));
        return $c;
    }
}
