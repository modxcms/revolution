<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Context;

use MODX\Revolution\modContext;
use MODX\Revolution\modAccessContext;
use MODX\Revolution\modResource;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Grabs a list of contexts.
 *
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @property string  $sort  (optional) The column to sort by. Defaults to key.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Context
 */
class GetList extends GetListProcessor
{
    public $classKey = modContext::class;
    public $permission = 'view_context';
    public $languageTopics = ['context'];
    public $defaultSortField = 'key';

    public $canCreate = false;
    public $canEdit = false;
    public $canRemove = false;
    protected $coreContexts;

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
            'search' => '',
            'exclude' => 'creator'
        ]);
        $this->isGridFilter = $this->getProperty('isGridFilter', false);

        $this->canCreate = $this->modx->hasPermission('new_context');
        $this->canEdit = $this->modx->hasPermission('edit_context');
        $this->canRemove = $this->modx->hasPermission('delete_context');
        $this->coreContexts = $this->classKey::getCoreContexts();

        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'key:LIKE' => '%' . $query . '%',
                'OR:name:LIKE' => '%' . $query . '%',
                'OR:description:LIKE' => '%' . $query . '%'
            ]);
        }
        $exclude = $this->getProperty('exclude');
        if (!empty($exclude)) {
            $c->where([
                'key:NOT IN' => is_string($exclude) ? explode(',', $exclude) : $exclude,
            ]);
        }
        /*
            When this class is used to fetch data for a grid filter's store (combo),
            limit results to only those contexts present in the current grid.
        */
        if ($this->isGridFilter) {
            $targetGrid = $this->getProperty('targetGrid', '');
            switch ($targetGrid) {
                case 'MODx.grid.UserGroupContext':
                    if ($userGroup = $this->getProperty('usergroup', false)) {
                        $c->innerJoin(
                            modAccessContext::class,
                            'modAccessContext',
                            [
                                '`modAccessContext`.`target` = `modContext`.`key`',
                                '`modAccessContext`.`principal` = ' . (int)$userGroup,
                                '`modAccessContext`.`principal_class` = ' . $this->modx->quote(modUserGroup::class)
                            ]
                        );
                        if ($policy = $this->getProperty('policy', false)) {
                            $c->where([
                                '`modAccessContext`.`policy`' => (int)$policy
                            ]);
                        }
                    }
                    break;

                case 'MODx.grid.Trash':
                    $c->innerJoin(
                        modResource::class,
                        'modResource',
                        [
                            '`modResource`.`context_key` = `modContext`.`key`',
                            '`modResource`.`deleted` = 1'
                            ]
                    );
                    break;

                // no default case
            }
        }
        return $c;
    }

    /**
     * Filter the query by the valueField of MODx.combo.Context to get the initially value displayed right
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $key = $this->getProperty('key', '');
        if (!empty($key)) {
            $c->where([
                $c->getAlias() . '.key:IN' => is_string($key) ? explode(',', $key) : $key,
            ]);
        }

        return $c;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject|modContext $object
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

        $contextData = $object->toArray();
        $contextKey = $contextData['key'];
        $isCoreContext = $object->isCoreContext($contextKey);

        if ($isCoreContext) {
            $object->setTranslatedCoreDescriptors($contextData);
        }

        $contextData['reserved'] = ['key' => $this->coreContexts, 'name' => ['Manager']];
        $contextData['isProtected'] = $isCoreContext;
        $contextData['creator'] = $isCoreContext ? 'modx' : strtolower($this->modx->lexicon('user')) ;
        if ($isCoreContext) {
            unset($permissions['delete']);
        }
        $contextData['permissions'] = $permissions;

        return $contextData;
    }
}
