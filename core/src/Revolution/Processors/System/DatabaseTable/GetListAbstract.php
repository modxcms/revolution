<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DatabaseTable;

use MODX\Revolution\Processors\DriverSpecificProcessor;

/**
 * Gets a list of database tables
 * @package modx
 * @subpackage processors.system.databasetable
 */
abstract class GetListAbstract extends DriverSpecificProcessor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('database');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['system_info'];
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        $tables = $this->getTables();
        if (empty($tables)) {
            $tables = [];
        }
        return $this->outputArray($tables);
    }

    /**
     * @return array
     */
    abstract public function getTables();

    /**
     * @param $size
     * @return string
     */
    public function formatSize($size)
    {
        if (!isset($size) || !is_numeric($size) || $size === 0) {
            return '0 B';
        }
        $a = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return $size === 0 ? '-' : round($size, 2) . ' ' . $a[$pos];
    }
}
