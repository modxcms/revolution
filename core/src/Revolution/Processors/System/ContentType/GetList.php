<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\ContentType;

use MODX\Revolution\modContentType;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of content types
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\ContentType
 */
class GetList extends GetListProcessor
{
    public $classKey = modContentType::class;
    public $languageTopics = ['content_type'];

    public $canCreate = false;
    public $canEdit = false;
    public $canRemove = false;

    protected $coreContentTypes;

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $canManage = $this->modx->hasPermission('content_types');
        $this->canCreate = $canManage;
        $this->canEdit = $canManage;
        $this->canRemove = $canManage;
        $this->coreContentTypes = $this->classKey::getCoreContentTypes();

        return $initialized;
    }

    /**
     * Filter the query by the valueField of MODx.combo.ContentType to get the initially value displayed right
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
     * @param xPDOObject|modContentType $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $permissions = [
            'create' => $this->canCreate,
            'duplicate' => $this->canCreate,
            'update' => $this->canEdit,
            'delete' => $this->canRemove
        ];
        $contentTypeData = $object->toArray();
        $dashboardName = $object->get('name');
        $isCoreContentType = $object->isCoreContentType($dashboardName);

        $contentTypeData['isProtected'] = $isCoreContentType;
        $contentTypeData['creator'] = $isCoreContentType ? 'modx' : strtolower($this->modx->lexicon('user')) ;
        if ($isCoreContentType) {
            unset($permissions['delete']);
        }
        $contentTypeData['permissions'] = $permissions;

        return $contentTypeData;
    }
}
