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
use MODX\Revolution\Transport\modTransportPackage;
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

    protected $canCreate = false;
    protected $canUpdate = false;
    protected $canDelete = false;

    protected $coreNamespaces;
    protected $extrasNamespaces = [];

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->isGridFilter = $this->getProperty('isGridFilter', false);
        $this->setDefaultProperties([
            'search' => false,
            'exclude' => 'creator'
        ]);

        /*
            Normally these would access permission like this:
            $this->canCreate = $this->modx->hasPermission('[object type]_save');
            Namespaces do not currently have changeable policy permissions, so
            setting each to true; consider adding new permissions settings for
            - namespace_save
            - namespace_edit
            - namespace_delete
        */
        $this->canCreate = $this->modx->hasPermission('namespaces');
        $this->canUpdate = $this->modx->hasPermission('namespaces');
        $this->canDelete = $this->modx->hasPermission('namespaces');
        $this->coreNamespaces = $this->classKey::getCoreNamespaces();
        $this->extrasNamespaces = $this->getExtrasNamespaces();

        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeQuery()
    {
        /*
            Implementing a little trick here since 'creator' (used for distinguishing core/protected row data
            from user-created data) is an arbitrary field not present in the database, and the grid
            utilizing this processor uses remote sorting.
        */
        if ($this->getProperty('sort') === 'creator') {
            $names = implode(',', array_map(function ($name) {
                return '"' . $name . '"';
            }, array_merge($this->coreNamespaces, $this->extrasNamespaces)));
            $this->setProperty('sort', 'FIELD(modNamespace.name, ' . $names . ')');
            $dir = $this->getProperty('dir') === 'ASC' ? 'DESC' : 'ASC' ;
            $this->setProperty('dir', $dir);
        }
        return true;
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
                    $namespaces = $this->modx->getObject($settingsClass, $nsSubquery)->get('namespaces');

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

    public function getExtrasNamespaces()
    {
        $namespaceList = [];

        $c = $this->modx->newQuery(modTransportPackage::class);
        $c->select([
            'name' => 'DISTINCT SUBSTRING_INDEX(`signature`,"-",1)'
        ]);
        // $c->prepare();
        // $this->modx->log(
        //     \modX::LOG_LEVEL_ERROR,
        //     "namespace packages query:\r\t" . $c->toSQL()
        // );
        $namespaces = $this->modx->getIterator(modTransportPackage::class, $c);
        $namespaces->rewind();
        if ($namespaces->valid()) {
            foreach ($namespaces as $namespace) {
                $namespaceList[] = $namespace->get('name');
            }
        }
        // $this->modx->log(
        //     \modX::LOG_LEVEL_ERROR,
        //     "namespaces arr:\r\t" . print_r($namespaceList, true)
        // );
        return $namespaceList;
    }

    /**
     * Prepare the Namespace for listing
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        /*
            If policy permissions get added for namespaces, change to:
            $permissions = [
                'create' => $this->canCreate && $object->checkPolicy('save'),
                'duplicate' => $this->canCreate && $object->checkPolicy('copy'),
                'update' => $this->canUpdate && $object->checkPolicy('save'),
                'delete' => $this->canDelete && $object->checkPolicy('remove')
            ];
        */
        $permissions = [
            'create' => $this->canCreate,
            'duplicate' => $this->canCreate,
            'update' => $this->canUpdate,
            'delete' => $this->canDelete
        ];

        $namespaceData = $object->toArray();
        $namespaceName = $object->get('name');
        $isCoreNamespace = $object->isCoreNamespace($namespaceName);

        $namespaceData['reserved'] = ['name' => $this->coreNamespaces];
        $namespaceData['isProtected'] = true;
        $namespaceData['isExtrasNamespace'] = true;

        switch (true) {
            case in_array($namespaceName, $this->extrasNamespaces):
                $namespaceData['creator'] = 'extra';
                break;
            case $isCoreNamespace:
                $namespaceData['creator'] = 'modx';
                $namespaceData['isExtrasNamespace'] = false;
                break;
            default:
                $namespaceData['creator'] = 'user';
                $namespaceData['isExtrasNamespace'] = false;
                $namespaceData['isProtected'] = false;
        }

        if ($isCoreNamespace) {
            unset($permissions['delete']);
        }
        $namespaceData['permissions'] = $permissions;

        return $namespaceData;
    }
}
