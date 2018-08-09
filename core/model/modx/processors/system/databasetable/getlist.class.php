<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Gets a list of database tables
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableGetListProcessor extends modDriverSpecificProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('database');
    }
    public function getLanguageTopics() {
        return array('system_info');
    }
    
    public function process() {
        $tables = $this->getTables();
        if (empty($tables)) $tables = array();
        return $this->outputArray($tables);
    }

    public function getTables() {return array();}

    public function formatSize($size) {
        if (!isset($size) || !is_numeric($size) || $size == 0) return '0 B';
        $a = array('B','KB','MB','GB','TB','PB');
        $pos = 0;
        while ($size >= 1024) {
               $size /= 1024;
               $pos++;
        }
        return $size == 0 ? '-' : round($size,2).' '.$a[$pos];
    }
}

/**
 * MySQL-specific table listing processor
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableGetListProcessor_mysql extends modDatabaseTableGetListProcessor {
    public function getTables() {
        $c = new xPDOCriteria($this->modx, 'SHOW TABLE STATUS FROM '.$this->modx->escape($this->modx->getOption('dbname')));
        $c->stmt->execute();
        $dt = array();
        while ($row= $c->stmt->fetch(PDO::FETCH_ASSOC)) {
            /* calculations first */
            if ($this->modx->hasPermission('settings') && $row['Name'] == $this->modx->getOption('table_prefix').'event_log' && $row['Data_length'] + $row['Data_free']>0) {
                $row['Data_size'] = '<a href="javascript:;" onclick="truncate(\''.$row['Name'].'\');" title="'.$this->modx->lexicon('truncate_table').'">'. $this->formatSize($row['Data_length'] + $row['Data_free']).'</a>';
            } else {
                $row['Data_size'] = $this->formatSize($row['Data_length'] + $row['Data_free']);
            }
            $row['Effective_size'] = $this->formatSize($row['Data_length'] - $row['Data_free']);
            $row['Total_size'] = $this->formatSize($row['Index_length'] + $row['Data_length'] + $row['Data_free']);

            /* now the non-calculated fields */
            $row['Data_length'] = $this->formatSize($row['Data_length']);
            if ($this->modx->hasPermission('settings') && $row['Data_free']>0) {
                $row['Data_free'] = '<a href="javascript:;" onclick="optimize(\''.$row['Name'].'\');" title="'.$this->modx->lexicon('optimize_table').'">'.$this->formatSize($row['Data_free']).'</a>';
            } else {
                $row['Data_free'] = $this->formatSize($row['Data_free']);
            }
            $row['Index_length'] = $this->formatSize($row['Index_length']);
            $dt[] = $row;
        }
        return $dt;
    }
}

/**
 * SQLSRV-specific table listing processor
 * 
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableGetListProcessor_sqlsrv extends modDatabaseTableGetListProcessor {
    public function getTables() {
        $c = new xPDOCriteria($this->modx, "select [name] from sys.Tables where [type] = 'U' ORDER BY [name]");
        $c->stmt->execute();
        $table_names = $c->stmt->fetchAll(PDO::FETCH_COLUMN);

        $dt = array();
        foreach($table_names as $table_name) {
            $c = new xPDOCriteria($this->modx, "exec sp_spaceused " . $this->modx->escape($table_name));
            $c->stmt->execute();
            $row = $c->stmt->fetch(PDO::FETCH_ASSOC);
            $row['Name'] = $row['name'];
            $row['Rows'] = $row['rows'];

            /* calculations first */
            if ($this->modx->hasPermission('settings') && $row['name'] == $this->modx->getOption('table_prefix').'event_log' && $row['data'] + $row['unused']>0) {
                $row['Data_size'] = '<a href="javascript:;" onclick="truncate(\''.$row['name'].'\');" title="'.$this->modx->lexicon('truncate_table').'">'. $row['data'] . '</a>';
            } else {
                $row['Data_size'] = $row['data'];
            }

            /* now the non-calculated fields */
            $row['Reserved'] = $row['reserved'];
            $row['Data_length'] = $row['data'];
            if ($this->modx->hasPermission('settings') && $row['unused']>0) {
                $row['Data_free'] = '<a href="javascript:;" onclick="optimize(\''.$row['name'].'\');" title="'.$this->modx->lexicon('optimize_table').'">'.$row['unused'].'</a>';
            } else {
                $row['Data_free'] = $row['unused'];
            }
            $row['Index_length'] = $row['index_size'];
            $dt[] = $row;
        }

        return $dt;
    }
}
return 'modDatabaseTableGetListProcessor';
