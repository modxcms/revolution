<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
        $id = $this->getProperty('id','');
        if (!empty($id)) {
            $c->where(array(
                $this->classKey . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ));
        }
        return $c;
    }
}
