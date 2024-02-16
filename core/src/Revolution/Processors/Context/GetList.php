<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context;


use MODX\Revolution\modContext;
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
    /** @var boolean $canEdit Determines whether or not the user can edit a Context */
    public $canEdit = false;
    /** @var boolean $canRemove Determines whether or not the user can remove a Context */
    public $canRemove = false;
    /** @var boolean $canCreate Determines whether or not the user can create a context (/duplicate one) */
    public $canCreate = false;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'search' => '',
            'exclude' => '',
        ]);
        $this->canCreate = $this->modx->hasPermission('new_context');
        $this->canEdit = $this->modx->hasPermission('edit_context');
        $this->canRemove = $this->modx->hasPermission('delete_context');

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
        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where([
                'key:LIKE' => '%' . $search . '%',
                'OR:description:LIKE' => '%' . $search . '%',
            ]);
        }
        $exclude = $this->getProperty('exclude');
        if (!empty($exclude)) {
            $c->where([
                'key:NOT IN' => is_string($exclude) ? explode(',', $exclude) : $exclude,
            ]);
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
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $contextArray = $object->toArray();
        $contextArray['perm'] = [];
        if ($this->canCreate) {
            $contextArray['perm'][] = 'pnew';
        }
        if ($this->canEdit) {
            $contextArray['perm'][] = 'pedit';
        }
        if (!in_array($object->get('key'), $this->classKey::RESERVED_KEYS) && $this->canRemove) {
            $contextArray['perm'][] = 'premove';
        }

        return $contextArray;
    }
}
