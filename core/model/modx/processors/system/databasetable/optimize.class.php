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
 * Optimize a database table
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableOptimizeProcessor extends modDriverSpecificProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('database');
    }
    public function getLanguageTopics() {
        return array('system_info');
    }

    public function process() {
        $table = $this->getProperty('t',false);
        if (empty($table)) return $this->failure($this->modx->lexicon('optimize_table_err'));

        if (!$this->optimize($table)) {
            $output = $this->failure($this->modx->lexicon('optimize_table_err'));
        } else {
            $this->logManagerAction();
            $output = $this->success();
        }
        return $output;
    }

    /**
     * Optimize a database table
     * @param string $table
     * @return boolean
     */
    public function optimize($table) {return true;}

    /**
     * Log a manager action showing the optimized table
     * @return void
     */
    public function logManagerAction() {
        if ($this->modx->error->status) {
            $this->modx->logManagerAction('database_optimize','table',$this->getProperty('t'));
        }

    }
}

/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableOptimizeProcessor_mysql extends modDatabaseTableOptimizeProcessor {
    public function optimize($table) {
        $sql = 'OPTIMIZE TABLE '.$this->modx->escape($this->modx->getOption('dbname')).'.'.$this->modx->escape($table);
        return $this->modx->exec($sql) === false ? false : true;
    }
}

/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableOptimizeProcessor_sqlsrv extends modDatabaseTableOptimizeProcessor {
    public function optimize($table) {
        $sql = 'ALTER INDEX ALL ON ' . $this->modx->escape($table) . ' REBUILD';
        return $this->modx->exec($sql) === false ? false : true;
    }
}
return 'modDatabaseTableOptimizeProcessor';
