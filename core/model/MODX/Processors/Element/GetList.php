<?php

namespace MODX\Processors\Element;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOQuery;

/**
 * Abstract class for Get Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class GetList extends modObjectGetListProcessor
{
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('modCategory', 'Category');

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        $c->select([
            'category_name' => 'Category.category',
        ]);

        return $c;
    }
}
