<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Model;

use MODX\Revolution\modAccessibleObject;
use MODX\Revolution\Processors\ModelProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * A utility abstract class for defining getlist-based processors
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
abstract class GetListProcessor extends ModelProcessor
{
    public const CLASS_ALLOW_EDIT = 'pedit';
    public const CLASS_ALLOW_REMOVE = 'premove';

    /** @var string $defaultSortField The default field to sort by */
    public $defaultSortField = 'name';
    /** @var string $defaultSortDirection The default direction to sort */
    public $defaultSortDirection = 'ASC';
    /** @var boolean $checkListPermission If true and object is a modAccessibleObject, will check list permission */
    public $checkListPermission = true;
    /** @var int $currentIndex The current index of successful iteration */
    public $currentIndex = 0;
    /** @var int $listTotal  An alternative and explicitly set count to be used in extending processors where the default getCount method will not work (e.g., when an accurate count depends upon an aggregated column) */
    public $listTotal = 0;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 20,
            'sort' => $this->defaultSortField,
            'dir' => $this->defaultSortDirection,
            'combo' => false,
            'query' => '',
        ]);

        return parent::initialize();
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();
        $list = $this->iterate($data);

        return $this->outputArray($list, $data['total']);
    }

    /**
     * Allow stoppage of process before the query
     *
     * @return boolean
     */
    public function beforeQuery()
    {
        return true;
    }

    /**
     * Iterate across the data
     *
     * @param array $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $list = [];
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) {
                continue;
            }
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);

        return $list;
    }

    /**
     * Can be used to insert a row before iteration
     *
     * @param array $list
     *
     * @return array
     */
    public function beforeIteration(array $list)
    {
        return $list;
    }

    /**
     * Can be used to insert a row after iteration
     *
     * @param array $list
     *
     * @return array
     */
    public function afterIteration(array $list)
    {
        return $list;
    }

    /**
     * Get the data of the query
     *
     * @return array
     */
    public function getData()
    {
        $data = [];
        $limit = (int)$this->getProperty('limit');
        $start = (int)$this->getProperty('start');

        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->listTotal ?: $this->modx->getCount($this->classKey, $c);
        $c = $this->prepareQueryAfterCount($c);

        $sortClassKey = $this->getSortClassKey();
        $sortAlias = $this->getSortClassKey();
        if (strpos($sortAlias, '\\') !== false) {
            $explodedAlias = explode('\\', $sortAlias);
            $sortAlias = array_pop($explodedAlias);
        }
        $sortKey = $this->modx->getSelectColumns(
            $sortClassKey,
            $this->getProperty('sortAlias', $sortAlias),
            '',
            [$this->getProperty('sort')]
        );
        if (empty($sortKey)) {
            $sortKey = $this->getProperty('sort');
        }
        $c->sortby($sortKey, $this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $data['results'] = $this->modx->getCollection($this->classKey, $c);

        return $data;
    }

    /**
     * Can be used to provide a custom sorting class key for the default sorting columns
     *
     * @return string
     */
    public function getSortClassKey()
    {
        return $this->classKey;
    }

    /**
     * Can be used to adjust the query prior to the COUNT statement
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        return $c;
    }

    /**
     * Can be used to prepare the query after the COUNT statement
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
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
        return $object->toArray();
    }
}
