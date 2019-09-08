<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User\Setting;

use MODX\Revolution\modUserSetting;

/**
 * Gets a list of user settings
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\User\Setting
 */
class GetList extends \MODX\Revolution\Processors\System\Settings\GetList
{
    public $classKey = modUserSetting::class;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties(['user' => 0]);

        return parent::initialize();
    }

    /**
     * Filter by user
     * @return array
     */
    public function prepareCriteria()
    {
        $criteria = [];
        $criteria[] = ['user' => (int)$this->getProperty('user')];
        
        return $criteria;
    }

}
