<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of recently edited resources by a user
 *
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserGetRecentlyEditedResourcesProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modManagerLog';
    public $permission = 'view_document';
    public $languageTopics = ['resource', 'user'];
    public $defaultSortField = 'occurred';
    public $defaultSortDirection = 'DESC';
    protected $classKeys = [];


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'limit' => 10,
        ]);

        $this->classKeys = $this->modx->getDescendants('modResource');
        $this->classKeys[] = 'modResource';

        return parent::initialize();
    }


    /**
     * Filter resources by user
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $q = $this->modx->newQuery($this->classKey, ['classKey:IN' => $this->classKeys]);
        $q->select('MAX(id), item');
        $q->groupby('item');
        $q->limit($this->getProperty('limit', 10));
        if ($q->prepare() && $q->stmt->execute()) {
            if ($ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
                $c->where(['id:IN' => $ids]);
            } else {
                $c->where(['id' => -1]);
            }
        }

        $c->select($this->modx->getSelectColumns('modManagerLog', 'modManagerLog'));

        return $c;
    }


    /**
     * Prepare the row for iteration
     *
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $row = $object->toArray();

        if (!$resource = $this->modx->getObject('modResource', ['id' => $row['item']])) {
            return [];
        }

        $resourceArray = $resource->get(array('id','pagetitle','description','published','deleted','context_key', 'editedon'));
        $resourceArray['pagetitle'] = htmlspecialchars($resourceArray['pagetitle'], ENT_QUOTES, $this->modx->getOption('modx_charset', null, 'UTF-8'));

        $row = array_merge($row, $resourceArray);

        /** @var modUser $user */
        if ($user = $object->getOne('User')) {
            $row = array_merge($row,
                $user->get(['username']),
                $user->Profile->get(['fullname', 'email']),
                ['photo' => $user->getPhoto(64, 64)]
            );
            /** @var modUserGroup $group */
            $row['group'] = ($group = $user->getOne('PrimaryGroup'))
                ? $group->get('name')
                : '';
        }

        $row['menu'] = [];
        $row['menu'][] = [
            'text' => $this->modx->lexicon('resource_overview'),
            'params' => [
                'a' => 'resource/data',
                'id' => $resource->get('id'),
                'type' => 'view',
            ],
        ];
        if ($this->modx->hasPermission('edit_document')) {
            $row['menu'][] = [
                'text' => $this->modx->lexicon('resource_edit'),
                'params' => [
                    'a' => 'resource/update',
                    'id' => $resource->get('id'),
                    'type' => 'edit',
                ],
            ];
        }
        $row['menu'][] = '-';
        $row['menu'][] = [
            'text' => $this->modx->lexicon('resource_preview'),
            'params' => [
                'url' => $this->modx->makeUrl($resource->get('id'), null, '', 'full'),
                'type' => 'open',
            ],
            'handler' => 'this.preview',
        ];

        return $row;
    }
}

return 'modUserGetRecentlyEditedResourcesProcessor';
