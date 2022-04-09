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
        $c = new xPDOCriteria($this->modx,
            'SHOW TABLE STATUS FROM ' . $this->modx->escape($this->modx->getOption('dbname')));
        $c->stmt->execute();
        $dt = [];
        while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
            /* calculations first */
            $row['canTruncate'] = $this->modx->hasPermission('settings') && $row['Name'] === $this->modx->getOption('table_prefix') . 'manager_log' && $row['Data_length'] + $row['Data_free'] > 0 ? true : false ;
            $row['Data_size'] = $this->formatSize($row['Data_length'] + $row['Data_free']);
            $row['Effective_size'] = $this->formatSize($row['Data_length'] - $row['Data_free']);
            $row['Total_size'] = $this->formatSize($row['Index_length'] + $row['Data_length'] + $row['Data_free']);

            /* now the non-calculated fields */
            $row['Data_length'] = $this->formatSize($row['Data_length']);
            $row['Data_free'] = $this->formatSize($row['Data_free']);
            $row['canOptimize'] = $this->modx->hasPermission('settings') && $row['Data_free'] > 0 ? true : false ;
            $row['Index_length'] = $this->formatSize($row['Index_length']);
            $dt[] = $row;
        }
        return $dt;
    }
}
