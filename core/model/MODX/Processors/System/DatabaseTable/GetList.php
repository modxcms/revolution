<?php

namespace MODX\Processors\System\DatabaseTable;

use MODX\Processors\modDriverSpecificProcessor;

/**
 * Gets a list of database tables
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
class GetList extends modDriverSpecificProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('database');
    }


    public function getLanguageTopics()
    {
        return ['system_info'];
    }


    public function process()
    {
        $tables = $this->getTables();
        if (empty($tables)) $tables = [];

        return $this->outputArray($tables);
    }


    public function getTables()
    {
        return [];
    }


    public function formatSize($size)
    {
        if (!isset($size) || !is_numeric($size) || $size == 0) return '0 B';
        $a = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }

        return $size == 0 ? '-' : round($size, 2) . ' ' . $a[$pos];
    }
}
