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
 * Gets a list of user settings given an array of keys to search for
 *
 * @property int $user The user to filter by
 * @property string $keys A json-formatted list of settings keys to additionally filter by
 * @package MODX\Revolution\Processors\Security\User\Setting
 */
class GetListIn extends \MODX\Revolution\Processors\System\Settings\GetList
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
     * Filter by user and a mulitple-key query
     * @return array
     */
    public function prepareCriteria()
    {
        $criteria = [];
        $criteria[] = ['user' => (int)$this->getProperty('user')];

        if ($keys = $this->getProperty('keys', '')) {
            $keys = json_decode($keys);
            $criteria[] = ['key:IN' => $keys];
        }
        return $criteria;
    }
}
