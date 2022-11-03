<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Permission;

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * @package MODX\Revolution\Processors\Security\Access\Permission
 */
class GetList extends GetListProcessor
{
    public $classKey = modAccessPermission::class;
    public $checkListPermission = false;
    public $objectType = 'permission';
    public $permission = 'access_permissions';
    public $languageTopics = ['access', 'permission'];

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties(['query' => '']);

        return $initialized;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $totalQuery = $this->modx->newQuery($this->classKey);
        $totalQuery->select([
            'total' => 'COUNT(DISTINCT `modAccessPermission`.`name`)'
        ]);

        $c->leftJoin(modAccessPolicyTemplate::class, 'Template');

        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $query = ['modAccessPermission.name:LIKE' => '%' . $query . '%'];
            $c->where($query);
            $totalQuery->where($query);
        }

        $this->listTotal = $this->modx->getObject($this->classKey, $totalQuery)->get('total');

        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select([
            'id' => 'MIN(modAccessPermission.id)',
            'modAccessPermission.name',
            'modAccessPermission.description',
            'Template.lexicon'
        ]);
        $c->groupby('modAccessPermission.name, modAccessPermission.description, Template.lexicon');

        $name = $this->getProperty('name', '');
        if (!empty($name)) {
            $c->where([
                $c->getAlias() . '.name:IN' => is_string($name) ? explode(',', $name) : $name
            ]);
        }

        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array|mixed
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->get(['name', 'description']);

        $lexicon = $object->get('lexicon');
        if (!empty($lexicon)) {
            if (strpos($lexicon, ':') !== false) {
                $this->modx->lexicon->load($lexicon);
            } else {
                $this->modx->lexicon->load('core:' . $lexicon);
            }
        }
        $objectArray['description'] = $this->modx->lexicon($objectArray['description']);

        return $objectArray;
    }
}
