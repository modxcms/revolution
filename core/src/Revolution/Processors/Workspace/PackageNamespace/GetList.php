<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\PackageNamespace;

use MODX\Revolution\modAccessNamespace;
use MODX\Revolution\modNamespace;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modUserGroupSetting;
use MODX\Revolution\modUserSetting;

/**
 * Gets a list of namespaces
 * @param string $name (optional) If set, will search by name
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Workspace\PackageNamespace
 */
class GetList extends GetListProcessor
{
    public $classKey = modNamespace::class;
    public $languageTopics = ['namespace', 'workspace'];
    public $permission = 'namespaces';

    /** @param boolean $isGridFilter Indicates the target of this list data is a filter field */
    protected $isGridFilter = false;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'query' => ''
        ]);
        $this->isGridFilter = $this->getProperty('isGridFilter', false);
        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'name:LIKE' => '%' . $query . '%',
                'OR:path:LIKE' => '%' . $query . '%',
            ]);
        }

        // $foreignKey is the primary key of the child settings entity (e.g., user, usergroup, context)
        $foreignKey = $this->getProperty('foreignKey', '');
        $foreignKeyWhere = null;

        /*
            When this class is used to fetch data for a grid filter's store (combo),
            limit results to only those namespaces present in the current grid.
        */
        if ($this->isGridFilter && $targetGrid = $this->getProperty('targetGrid', false)) {
            switch ($targetGrid) {
                case 'MODx.grid.SettingsGrid':
                    $settingsType = $this->getProperty('targetSettingsType', 'system');
                    $alias = 'settingsNamespace';
                    switch ($settingsType) {
                        case 'context':
                            $settingsClass = modContextSetting::class;
                            $foreignKeyWhere = $foreignKey ? [ $alias . '.context_key' => $this->modx->sanitizeString($foreignKey) ] : null ;
                            break;
                        case 'group':
                            $settingsClass = modUserGroupSetting::class;
                            $foreignKeyWhere = $foreignKey ? [ $alias . '.group' => (int)$foreignKey ] : null ;
                            break;
                        case 'system':
                            $settingsClass = modSystemSetting::class;
                            break;
                        case 'user':
                            $settingsClass = modUserSetting::class;
                            $foreignKeyWhere = $foreignKey ? [ $alias . '.user' => (int)$foreignKey ] : null ;
                            break;
                        // no default
                    }

                    $nsSubquery = $this->modx->newQuery($settingsClass);
                    $nsSubquery->setClassAlias($alias);
                    $nsSubquery->select([
                        'namespaces' => "GROUP_CONCAT(DISTINCT `{$alias}`.`namespace` SEPARATOR '\",\"')"
                    ]);
                    if ($area = $this->getProperty('area', false)) {
                        $nsSubquery->where([
                            "`{$alias}`.`area`" => $area
                        ]);
                    }
                    if ($foreignKeyWhere) {
                        $nsSubquery->where($foreignKeyWhere);
                    }
                    $namespaces = $this->modx->getObject($settingsClass, $nsSubquery, false)->get('namespaces');

                    $c->where(
                        "`{$c->getAlias()}`.`name` IN (\"{$namespaces}\")"
                    );
                    break;
                case 'MODx.grid.UserGroupNamespace':
                    if ($userGroup = $this->getProperty('usergroup', false)) {
                        $c->innerJoin(
                            modAccessNamespace::class,
                            'modAccessNamespace',
                            [
                                '`modAccessNamespace`.`target` = `modNamespace`.`name`',
                                '`modAccessNamespace`.`principal` = ' . (int)$userGroup,
                                '`modAccessNamespace`.`principal_class` = ' . $this->modx->quote(modUserGroup::class)
                            ]
                        );
                        if ($policy = $this->getProperty('policy', false)) {
                            $c->where([
                                '`modAccessNamespace`.`policy`' => (int)$policy
                            ]);
                        }
                    }
                    break;
                case 'MODx.grid.Lexicon':
                    $language = $this->getProperty('language', 'en');
                    $topic = $this->getProperty('topic', '');
                    $namespaces = $this->modx->lexicon->getNamespaceList($language, $topic);
                    if (!empty($namespaces)) {
                        $c->where([
                            "`{$c->getAlias()}`.`name`:IN" => $namespaces
                        ]);
                    }
                    break;
                // no default
            }
        }
        return $c;
    }

    /**
     * Filter the query by the name property to get the right value in preselectFirstValue of MODx.combo.Namespace
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $name = $this->getProperty('name', '');
        if (!empty($name)) {
            $c->where([$c->getAlias() . '.name:IN' => is_string($name) ? explode(',', $name) : $name]);
        }
        return $c;
    }

    /**
     * Prepare the Namespace for listing
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['perm'] = [];
        $objectArray['perm'][] = 'pedit';
        $objectArray['perm'][] = 'premove';

        return $objectArray;
    }
}
