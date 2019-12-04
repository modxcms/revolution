<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\ActiveResource;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modUser;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of active resources
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @param string $dateFormat (optional) The strftime date format to format the
 * editedon date to. Defaults to: %b %d, %Y %I:%M %p
 * @package MODX\Revolution\Processors\System\ActiveResource
 */
class GetList extends GetListProcessor
{
    public $classKey = modResource::class;
    public $languageTopics = ['resource'];
    public $permission = 'view_document';
    public $defaultSortField = 'editedon';
    public $defaultSortDirection = 'DESC';

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_document');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['resource'];
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'dateFormat' => '%b %d, %Y %I:%M %p',
        ]);
        return $initialized;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin(modUser::class, 'EditedBy');
        $c->where([
            'deleted' => 0,
            'editedon:!=' => null,
        ]);
        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns(modResource::class, 'modResource'));
        $c->select($this->modx->getSelectColumns(modUser::class, 'EditedBy', '', ['username']));
        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array|mixed
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->get([
            'id',
            'pagetitle',
            'editedon',
            'username',
        ]);
        $objectArray['editedon'] = strftime($this->getProperty('dateFormat'), strtotime($object->get('editedon')));

        return $objectArray;
    }
}
