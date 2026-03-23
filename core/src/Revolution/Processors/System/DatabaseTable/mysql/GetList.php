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
     * @return array
     */
/**
    * Fetch the status data for every table in the current database
    */
public function getTables(): array
    {
        $dbName = $this->getDatabaseName();
        if ($dbName === null || $dbName === '') {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('database_query_err_dbname_empty'));
            return [];
        }

        $c = new xPDOCriteria($this->modx,
            'SHOW TABLE STATUS FROM ' . $this->modx->escape($dbName));
        if ($c->stmt === null) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('database_query_err_table_stat', ['db' => $dbName]));
            return [];
        }
        $c->stmt->execute();

        $canManageSettings = $this->modx->hasPermission('settings');
        $managerLogTable = $this->modx->getOption('table_prefix') . 'manager_log';
        $tables = [];

        while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
            $tables[] = $this->formatTableRow($row, $canManageSettings, $managerLogTable);
        }

        return $tables;
    }

    /**
     * @param array  $row
     * @param bool   $canManageSettings
     * @param string $managerLogTable
     * @return array
     */
    /**
     * Calculates and formats a table's status-related attributes
     * @param array $row The collection of table data being formatted
     * @param bool $canManageSettings Whether the current user has permissions to manipulate table data
     * @param string $managerLogTable The name, including prefix, of the table containing the manager logs
     */
    protected function formatTableRow(array $row, bool $canManageSettings, string $managerLogTable): array
    {
        $dataLength = (int) $row['Data_length'];
        $dataFree = (int) $row['Data_free'];
        $indexLength = (int) $row['Index_length'];

        $row['canTruncate'] = $canManageSettings
            && $row['Name'] === $managerLogTable
            && $dataLength + $dataFree > 0;
        $row['Data_size'] = $this->formatSize($dataLength + $dataFree);
        $row['Effective_size'] = $this->formatSize(max(0, $dataLength - $dataFree));
        $row['Total_size'] = $this->formatSize($indexLength + $dataLength + $dataFree);
        $row['Data_length'] = $this->formatSize($dataLength);
        $row['Data_free'] = $this->formatSize($dataFree);
        $row['canOptimize'] = $canManageSettings && $dataFree > 0;
        $row['Index_length'] = $this->formatSize($indexLength);

        return $row;
    }

    /**
     * Current database name from connection (MySQL 8–compatible), fallback to config.
     */
    protected function getDatabaseName(): ?string
    {
        try {
            $stmt = $this->modx->query('SELECT DATABASE()');
            if ($stmt !== false) {
                $name = $stmt->fetchColumn();
                if ($name !== false && $name !== null && $name !== '') {
                    return $name;
                }
            }
        } catch (\Throwable $e) {
            // use config fallback
        }

        $name = (string) $this->modx->getOption('dbname');
        return $name !== '' ? $name : null;
    }
}
