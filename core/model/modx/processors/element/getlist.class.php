<?php
/**
 * Abstract class for Get Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class modElementGetListProcessor extends modObjectGetListProcessor {
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modCategory','Category');
        return $c;
    }
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns($this->classKey,$this->classKey));
        $c->select(array(
            'category_name' => 'Category.category',
        ));
        return $c;
    }
}