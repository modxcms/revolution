<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DatabaseTable\mysql;

use MODX\Revolution\modX;
use PDO;
use xPDO\Om\xPDOCriteria;

/**
 * MySQL-specific table listing processor
 * @package MODX\Revolution\Processors\System\DatabaseTable\mysql
 */
class GetList extends \MODX\Revolution\Processors\System\DatabaseTable\GetListAbstract
{
    /**
     * Fetch the status data for every table in the current database
     */
    public function getTables(): array
    {
        $dbName = $this->modx->getOption('dbname');
        if (!$dbName) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('database_dbname_err_empty'));
            return [];
        }

        $c = new xPDOCriteria($this->modx, 'SHOW TABLE STATUS FROM ' . $this->modx->escape($dbName));
        if ($c->stmt === null) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('database_query_err_table_stat', ['db' => $dbName]));
            return [];
        }
        $c->stmt->execute();

        $tablePrefix = $this->modx->getOption('table_prefix');
        $permissions = [
            'canTruncate' => $this->modx->hasPermission('database_truncate'),
            'canOptimize' => $this->modx->hasPermission('settings')
        ];
        $truncateWhitelist = array_map(fn(string $table): string => $tablePrefix . $table, [
            // Add un-prefixed table names that are candidates for truncation to this array
            'manager_log'
        ]);
        $tables = [];

        while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
            $tables[] = $this->formatTableRow($row, $permissions, $truncateWhitelist);
        }

        return $tables;
    }

    /**
     * Calculates and formats a table's status-related attributes
     * @param array $row The collection of table data being formatted
     * @param array $permissions The current user's permissions to manipulate table data
     * @param array $truncateWhitelist A list of tables that are candidates for truncation
     */
    protected function formatTableRow(array $row, array $permissions, array $truncateWhitelist): array
    {
        $dataLength = (int) $row['Data_length'];
        $dataFree = (int) $row['Data_free'];
        $indexLength = (int) $row['Index_length'];

        $row['Data_size'] = $this->formatSize($dataLength);
        $row['Effective_size'] = $this->formatSize($dataLength + $indexLength);
        $row['Total_size'] = $this->formatSize($dataLength + $indexLength);
        $row['Data_length'] = $this->formatSize($dataLength);
        $row['Data_free'] = $this->formatSize($dataFree);
        $row['Index_length'] = $this->formatSize($indexLength);
        $row['canTruncate'] = $permissions['canTruncate'] && in_array($row['Name'], $truncateWhitelist) && $dataLength > 0;
        $row['canOptimize'] = $permissions['canOptimize'] && $dataFree > 0;

        return $row;
    }
}
