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

use MODX\Revolution\Processors\Model\GetProcessor;
use MODX\Revolution\modUserSetting;

/**
 * Gets a list of user settings given an array of keys to search for
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
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
        // $msg = "\r\n prepareCriteria, \$properties:\r\n" . print_r($this->getProperties(), true);
        // $this->modx->log(\modX::LOG_LEVEL_ERROR, $msg, '', __CLASS__);

        if ($keys = $this->getProperty('keys', '')) {
            $keys = json_decode($keys);
            // $this->modx->log(
            //     \modX::LOG_LEVEL_ERROR,
            //     "\r\t prepareCriteria:
            //     \t\t\$var1: {none}
            //     \t\t\$keys: " . print_r($keys, true)
            // );
            $criteria[] = ['key:IN' => $keys];
        }
        return $criteria;
    }

}
