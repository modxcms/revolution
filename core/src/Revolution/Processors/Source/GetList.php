<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Source;

use MODX\Revolution\modAccessibleObject;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\Sources\modMediaSource;
use MODX\Revolution\Sources\modAccessMediaSource;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of Media Sources
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Source
 */
class GetList extends GetListProcessor
{
    public $classKey = modMediaSource::class;
    public $languageTopics = ['source'];
    public $permission = 'source_view';

    /** @param boolean $isGridFilter Indicates the target of this list data is a filter field */
    protected $isGridFilter = false;

    public $canCreate = false;
    public $canEdit = false;
    public $canRemove = false;

    protected $coreSources;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'showNone' => false,
            'query' => '',
            'streamsOnly' => false,
            'exclude' => 'creator'
        ]);
        $this->isGridFilter = $this->getProperty('isGridFilter', false);

        $this->canCreate = $this->modx->hasPermission('source_save');
        $this->canEdit = $this->modx->hasPermission('source_edit');
        $this->canRemove = $this->modx->hasPermission('source_delete');
        $this->coreSources = $this->classKey::getCoreSources();

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
            }, $this->coreSources));
            $this->setProperty('sort', 'FIELD(modMediaSource.name, ' . $names . ')');
            $dir = $this->getProperty('dir') === 'ASC' ? 'DESC' : 'ASC' ;
            $this->setProperty('dir', $dir);
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        if ($this->getProperty('showNone')) {
            $list[] = [
                'id' => 0,
                'name' => '(' . $this->modx->lexicon('none') . ')',
                'description' => '',
            ];
        }
        return $list;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(['modMediaSource.name:LIKE' => '%' . $query . '%']);
            $c->orCondition(['modMediaSource.description:LIKE' => '%' . $query . '%']);
        }
        if ($this->getProperty('streamsOnly')) {
            $c->where([
                'modMediaSource.is_stream' => true,
            ]);
        }
        /*
            When this class is used to fetch data for a grid filter's store (combo),
            limit results to only those media sources present in the current grid.
        */
        if ($this->isGridFilter) {
            if ($userGroup = $this->getProperty('usergroup', false)) {
                $c->innerJoin(
                    modAccessMediaSource::class,
                    'modAccessMediaSource',
                    [
                        '`modAccessMediaSource`.`target` = `modMediaSource`.`id`',
                        '`modAccessMediaSource`.`principal` = ' . (int)$userGroup,
                        '`modAccessMediaSource`.`principal_class` = ' . $this->modx->quote(modUserGroup::class)
                    ]
                );
                if ($policy = $this->getProperty('policy', false)) {
                    $c->where([
                        '`modAccessMediaSource`.`policy`' => (int)$policy
                    ]);
                }
            }
        }
        return $c;
    }

    /**
     * Filter the query by the valueField of MODx.combo.MediaSource to get the initially value displayed right
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }
        return $c;
    }

    /**
     * {@inheritDoc}
     * @return string
     */
    public function getSortClassKey()
    {
        return modMediaSource::class;
    }

    /**
     * Prepare the source for iteration and output
     * @param xPDOObject|modAccessibleObject|modMediaSource $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $permissions = [
            'create' => $this->canCreate && $object->checkPolicy('save'),
            'duplicate' => $this->canCreate && $object->checkPolicy('copy'),
            'update' => $this->canEdit && $object->checkPolicy('save'),
            'delete' => $this->canRemove && $object->checkPolicy('remove')
        ];

        $sourceData = $object->toArray();
        $sourceName = $object->get('name');
        $isCoreSource = $object->isCoreSource($sourceName);

        if ($isCoreSource) {
            $baseKey = '_source_' . strtolower(str_replace(' ', '', $sourceName)) . '_';
            $sourceData['name_trans'] = $this->modx->lexicon($baseKey . 'name');
            $sourceData['description_trans'] = $this->modx->lexicon($baseKey . 'description');
        }

        $sourceData['reserved'] = ['name' => $this->coreSources];
        $sourceData['isProtected'] = $isCoreSource;
        $sourceData['creator'] = $isCoreSource ? 'modx' : strtolower($this->modx->lexicon('user')) ;
        if ($isCoreSource) {
            unset($permissions['delete']);
        }
        $sourceData['permissions'] = $permissions;

        return $sourceData;
    }
}
