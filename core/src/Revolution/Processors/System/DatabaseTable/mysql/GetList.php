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
    public function getTables()
    {
        $dbName = $this->getDatabaseName();
        if ($dbName === null || $dbName === '') {
            return [];
        }

        $c = new xPDOCriteria($this->modx,
            'SHOW TABLE STATUS FROM ' . $this->modx->escape($dbName));
        $c->stmt->execute();

        $canManageSettings = $this->modx->hasPermission('settings');
        $managerLogTable = $this->modx->getOption('table_prefix') . 'manager_log';
        $dt = [];

        while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
            $dt[] = $this->formatTableRow($row, $canManageSettings, $managerLogTable);
        }

        return $dt;
    }

    /**
     * @param array  $row
     * @param bool   $canManageSettings
     * @param string $managerLogTable
     * @return array
     */
    private function formatTableRow(array $row, bool $canManageSettings, string $managerLogTable)
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
     *
     * @return string|null
     */
    private function getDatabaseName()
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

        return $this->modx->getOption('dbname');
    }
}
