<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUserGroup;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of user groups
 * @param boolean $combo (optional) If true, will append a (anonymous) row
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\Group
 */
class GetList extends GetListProcessor
{
    public $classKey = modUserGroup::class;
    public $languageTopics = ['user', 'access', 'messages'];
    public $permission = 'usergroup_view';

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'addAll' => false,
            'addNone' => false,
            'combo' => false,
        ]);
        
        return $initialized;
    }

    /**
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        $query = $this->getProperty('query', '');
        $parent = $this->getProperty('parent', ''); // avoid 0 which is also a parent
        if (!empty($query) || $parent !== '') {
            return $list;
        }
        if ($this->getProperty('addAll', false)) {
            $list[] = [
                'id' => '',
                'name' => '(' . $this->modx->lexicon('all') . ')',
                'description' => '',
                'parent' => '',
            ];
        }
        if ($this->getProperty('addNone', false)) {
            $list[] = [
                'id' => 0,
                'name' => $this->modx->lexicon('none'),
                'description' => '',
                'parent' => 0,
            ];
        }
        if ($this->getProperty('combo', false)) {
            $list[] = [
                'id' => '',
                'name' => ' (' . $this->modx->lexicon('anonymous') . ') ',
                'description' => '',
                'parent' => 0,
            ];
        }

        return $list;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $exclude = $this->getProperty('exclude', '');
        if (!empty($exclude)) {
            $c->where([
                'id:NOT IN' => is_array($exclude) ? $exclude : explode(',', $exclude),
            ]);
        }
        $parent = $this->getProperty('parent', '');
        if (!empty($parent)) {
            $c->where(['parent' => $parent]);
        }
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'name:LIKE' => '%' . $query . '%',
                'OR:description:LIKE' => '%' . $query . '%',
            ]);
        }
        $c->sortby('parent', 'asc');
        $c->sortby('id', 'asc');
        
        return $c;
    }
}
