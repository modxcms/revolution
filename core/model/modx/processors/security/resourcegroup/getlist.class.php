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
 * Gets a list of resource groups
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class modResourceGroupGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modResourceGroup';
    public $languageTopics = array('access');
    public $permission = 'resourcegroup_view';

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $key = $this->getProperty('key','');
        if (!empty($key)) {
            $c->where(array(
                $this->classKey . '.key:IN' => is_string($key) ? explode(',', $key) : $key,
            ));
        }
        return $c;
    }
}
return 'modResourceGroupGetListProcessor';
