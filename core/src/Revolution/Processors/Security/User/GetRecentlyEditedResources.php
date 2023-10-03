<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User;

use MODX\Revolution\modManagerLog;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use PDO;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of recently edited resources by a user
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\User
 */
class GetRecentlyEditedResources extends GetListProcessor
{
    public $classKey = modManagerLog::class;
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
        $this->setDefaultProperties(['limit' => 10]);

        $this->classKeys = $this->modx->getDescendants(modResource::class);
        $this->classKeys[] = modResource::class;

        return parent::initialize();
    }


    /**
     * Filter resources by user
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $user = $this->getProperty('user');
        $q = $this->modx->newQuery($this->classKey, ['classKey:IN' => $this->classKeys]);
        $q->select('MAX(id)');
        if (!empty($user)) {
            $q->where(['user' => $user]);
        }
        $q->groupby('item');

        $sql = '-1';
        if ($q->prepare()) {
            $sql = $q->toSQL();
        }
        $c->select($this->modx->getSelectColumns(modManagerLog::class, 'modManagerLog'));
        $c->where(<<<SQL
id in ({$sql})
SQL
        );

        return $c;
    }


    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $row = $object->toArray();

        if (!$resource = $this->modx->getObject(modResource::class, ['id' => $row['item']])) {
            return [];
        }

        $resourceArray = $resource->get(['id','pagetitle','description','published','deleted','context_key', 'createdon', 'editedon']);
        $resourceArray['pagetitle'] = htmlspecialchars($resourceArray['pagetitle'], ENT_QUOTES, $this->modx->getOption('modx_charset', null, 'UTF-8'));

        $dateFormat = $this->modx->getOption('manager_date_format');
        $timeFormat = $this->modx->getOption('manager_time_format');

        $createdon = new \DateTimeImmutable($resourceArray['createdon']);
        $resourceArray['createdon_date'] = $createdon->format($dateFormat);
        $resourceArray['createdon_time'] = $createdon->format($timeFormat);

        $resourceArray['editedon_date'] = $resourceArray['createdon_date'];
        $resourceArray['editedon_time'] = $resourceArray['createdon_time'];

        if (!empty($resourceArray['editedon'])) {
            $editedon = new \DateTimeImmutable($resourceArray['editedon']);
            $resourceArray['editedon_date'] = $editedon->format($dateFormat);
            $resourceArray['editedon_time'] = $editedon->format($timeFormat);
        }

        $row = array_merge($row, $resourceArray);

        /** @var modUser $user */
        if ($user = $object->getOne('User')) {
            $row = array_merge($row,
                $user->get(['username']),
                $user->Profile->get(['fullname', 'email']),
                ['photo' => $user->getPhoto(64, 64)]
            );
            /** @var modUserGroup $group */
            $row['group'] = ($group = $user->getOne('PrimaryGroup')) ? $group->get('name') : '';
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
            'text'    => $this->modx->lexicon('resource_view'),
            'handler' => 'this.preview',
        ];

        $row['link'] = $this->modx->makeUrl($resource->get('id'), $resource->get('context_key'));

        return $row;
    }
}
