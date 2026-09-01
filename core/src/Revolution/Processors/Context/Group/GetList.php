<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context\Group;

use MODX\Revolution\modContext;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Get a list of Context Groups.
 *
 * @package MODX\Revolution\Processors\Context\Group
 */
class GetList extends GetListProcessor
{
    public $classKey = modContextGroup::class;
    public $languageTopics = ['context'];
    public $permission = 'view_context';
    public $defaultSortField = 'rank';
    public $defaultSortDirection = 'ASC';

    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'query' => '',
            'combo' => false,
            'showNone' => false,
        ]);

        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'name:LIKE' => '%' . $query . '%',
                'OR:description:LIKE' => '%' . $query . '%',
            ]);
        }

        return $c;
    }

    public function beforeIteration(array $list)
    {
        if ($this->getProperty('combo') && $this->getProperty('showNone')) {
            $noneLabel = $this->modx->lexicon('context_group_none');
            if ($noneLabel === 'context_group_none') {
                $noneLabel = $this->modx->lexicon('context_group_none', [], 'en');
            }
            $list[] = [
                'id' => 0,
                'name' => $noneLabel,
                'description' => '',
                'rank' => -1,
            ];
        }

        return $list;
    }

    public function prepareRow(xPDOObject $object)
    {
        $data = $object->toArray();
        $data['contexts'] = $this->modx->getCount(modContext::class, [
            'context_group' => $object->get('id'),
        ]);

        return $data;
    }
}
