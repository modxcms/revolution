<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\modCategory;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOQuery;

/**
 * Abstract class for Get Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class GetList extends GetListProcessor
{
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin(modCategory::class, 'Category');

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($c->getClass(), $c->getAlias()));
        $c->select([
            'category_name' => 'Category.category',
        ]);
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        return $c;
    }
}
