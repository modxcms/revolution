<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DatabaseTable\sqlsrv;

use PDO;
use xPDO\Om\xPDOCriteria;

/**
 * SQLSRV-specific table listing processor
 * @package MODX\Revolution\Processors\System\DatabaseTable\sqlsrv
 */
class GetList extends \MODX\Revolution\Processors\System\DatabaseTable\GetListAbstract
{
    /**
     * @return array
     */
    public function getTables()
    {
        $c = new xPDOCriteria($this->modx, "select [name] from sys.Tables where [type] = 'U' ORDER BY [name]");
        $c->stmt->execute();
        $table_names = $c->stmt->fetchAll(PDO::FETCH_COLUMN);

        $dt = [];
        foreach ($table_names as $table_name) {
            $c = new xPDOCriteria($this->modx, 'exec sp_spaceused ' . $this->modx->escape($table_name));
            $c->stmt->execute();
            $row = $c->stmt->fetch(PDO::FETCH_ASSOC);
            $row['Name'] = $row['name'];
            $row['Rows'] = $row['rows'];

            /* calculations first */
            if ($this->modx->hasPermission('settings') && $row['name'] === $this->modx->getOption('table_prefix') . 'event_log' && $row['data'] + $row['unused'] > 0) {
                $row['Data_size'] = '<a href="javascript:;" onclick="truncate(\'' . $row['name'] . '\')" title="' . $this->modx->lexicon('truncate_table') . '">' . $row['data'] . '</a>';
            } else {
                $row['Data_size'] = $row['data'];
            }

            /* now the non-calculated fields */
            $row['Reserved'] = $row['reserved'];
            $row['Data_length'] = $row['data'];
            if ($this->modx->hasPermission('settings') && $row['unused'] > 0) {
                $row['Data_free'] = '<a href="javascript:;" onclick="optimize(\'' . $row['name'] . '\')" title="' . $this->modx->lexicon('optimize_table') . '">' . $row['unused'] . '</a>';
            } else {
                $row['Data_free'] = $row['unused'];
            }
            $row['Index_length'] = $row['index_size'];
            $dt[] = $row;
        }

        return $dt;
    }
}
