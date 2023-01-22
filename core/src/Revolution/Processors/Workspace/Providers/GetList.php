<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Providers;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\Transport\modTransportProvider;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of providers
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Workspace\Providers
 */
class GetList extends GetListProcessor
{
    public $classKey = modTransportProvider::class;
    public $languageTopics = ['workspace'];
    public $permission = 'providers';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties(['combo' => false]);
        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @return string
     */
    public function getSortClassKey()
    {
        return modTransportProvider::class;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([$c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id]);
        }
        return $c;
    }

    /**
     * {@inheritDoc}
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        $isCombo = $this->getProperty('combo', false);
        if ($isCombo) {
            $list[] = ['id' => 0, 'name' => $this->modx->lexicon('none')];
        }
        return $list;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        if (!$this->getProperty('combo', false)) {
            $objectArray['menu'] = [
                [
                    'text' => $this->modx->lexicon('edit'),
                    'handler' => ['xtype' => 'modx-window-provider-update'],
                ],
                '-',
                [
                    'text' => $this->modx->lexicon('delete'),
                    'handler' => 'this.remove.createDelegate(this,["provider_confirm_remove", "Workspace/Providers/Remove"])',
                ]
            ];
        }
        return $objectArray;
    }
}
