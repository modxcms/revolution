<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Role;


use xPDO\Om\xPDOObject;

/**
 * Gets a list of roles
 *
 * @param boolean $addNone If true, will add a role of None
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to authority.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Security\Role
 */
class GetAuthorityList extends GetList
{
    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['name'] = $object->get('name') . ' - ' . $object->get('authority');
        $objectArray['id'] = $object->get('authority');

        return $objectArray;
    }
}
