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
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableTruncateProcessor extends modDriverSpecificProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('database_truncate');
    }
    public function getLanguageTopics() {
        return array('system_info');
    }

    public function process() {
        $table = $this->getProperty('t',false);
        if (empty($table)) return $this->failure($this->modx->lexicon('truncate_table_err'));

        if (!$this->truncate($table)) {
            $output = $this->failure($this->modx->lexicon('truncate_table_err'));
        } else {
            $this->logManagerAction();
            $output = $this->success();
        }
        return $output;
    }

    /**
     * Truncate a database table
     * @param string $table
     * @return boolean
     */
    public function truncate($table) {return true;}

    /**
     * Log a manager action showing the truncated table
     * @return void
     */
    public function logManagerAction() {
        if ($this->modx->error->status) {
            $this->modx->logManagerAction('database_truncate','table',$this->getProperty('t'));
        }

    }
}

/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableTruncateProcessor_mysql extends modDatabaseTableTruncateProcessor {
    public function truncate($table) {
        $sql = 'TRUNCATE TABLE '.$this->modx->escape($this->modx->getOption('dbname')).'.'.$this->modx->escape($table);
        if ($this->modx->exec($sql) === false) {
            return false;
        }
        return true;
    }
}

/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableTruncateProcessor_sqlsrv extends modDatabaseTableTruncateProcessor {
    public function truncate($table) {
        $sql = 'TRUNCATE TABLE '.$this->modx->escape($table);
        if ($this->modx->exec($sql) === false) {
            return false;
        }
        return true;
    }
}
return 'modDatabaseTableTruncateProcessor';
