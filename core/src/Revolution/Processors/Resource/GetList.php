<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modResource;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of resources.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @return array An array of modResources
 */
class GetList extends GetListProcessor
{
    public $classKey = modResource::class;
    public $languageTopics = ['resource'];
    public $defaultSortField = 'pagetitle';
    public $permission = 'view';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin(\MODX\Revolution\modContext::class, 'Context');

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $c->where([
                'pagetitle:LIKE'    => '%' . $query . '%',
                'OR:longtitle:LIKE' => '%' . $query . '%'
            ]);
        }

        $ignore = $this->getProperty('ignore');

        if (!empty($ignore)) {
            $c->where([
                'id:NOT IN' => explode(',', $ignore)
            ]);
        }

        $c->sortby('context_key');
        $c->sortby('pagetitle');

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $aliasArray = explode('\\',$this->classKey); // Get the alias
        $c->select($this->modx->getSelectColumns($this->classKey, end($aliasArray)));

        $c->select([
            'context_name' => 'Context.name'
        ]);

        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $charset = $this->modx->getOption('modx_charset', null, 'UTF-8');

        return [
            'id'            => $object->get('id'),
            'pagetitle'     => htmlentities($object->get('pagetitle'), ENT_COMPAT, $charset),
            'longtitle'     => htmlentities($object->get('longtitle'), ENT_COMPAT, $charset),
            'context_key'   => $object->get('context_key'),
            'context_name'  => $object->get('context_name'),
            'time'          => time()
        ];
    }
}
