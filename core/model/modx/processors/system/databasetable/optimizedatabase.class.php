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
class modDatabaseTableOptimizeDatabaseProcessor extends modDriverSpecificProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('database');
    }
    public function getLanguageTopics() {
        return array('system_info');
    }

    public function process() {
        $optimized = $this->optimize();
        if ($optimized !== true) {
            $output = $this->failure($this->modx->lexicon('optimize_database_err').$optimized);
        } else {
            $this->logManagerAction();
            $output = $this->success();
        }
        return $output;
    }

    /**
     * Optimize the database
     * @return boolean
     */
    public function optimize() {return true;}

    /**
     * Log a manager action showing the optimized table
     * @return void
     */
    public function logManagerAction() {
        if ($this->modx->error->status) {
            $this->modx->logManagerAction('database_optimize','database',0);
        }

    }
}

/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableOptimizeDatabaseProcessor_mysql extends modDatabaseTableOptimizeDatabaseProcessor {
    public function optimize() {
        $stmt = $this->modx->query('SHOW TABLES');
        if ($stmt && $stmt instanceof PDOStatement) {
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                if (!empty($row[0])) {
                    $sql = 'OPTIMIZE TABLE '.$this->modx->escape($this->modx->getOption('dbname')).'.'.$this->modx->escape($row[0]);
                    $this->modx->query($sql);
                }
            }
            $stmt->closeCursor();
        } else {
            return false;
        }
        return true;
    }
}

/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
class modDatabaseTableOptimizeDatabaseProcessor_sqlsrv extends modDatabaseTableOptimizeDatabaseProcessor {
    public function optimize() {
        $sql = file_get_contents(dirname(__FILE__) . '/defragment-indexes.sql');
        $sql = str_replace('[[+dbname]]', $this->modx->escape($this->modx->config['database']), $sql);
        $c = new xPDOCriteria($this->modx, $sql);
        if ($c->stmt->execute() === false) {
            return false;
        }
        $er = $c->stmt->errorInfo();
        if ($er) {
            $sqlstate_class = substr($er[0], 0, 2);
            $sqlstate_subclass = substr($er[0], 2);
            switch($sqlstate_class) {
                case '00':
                    // success
                    return true;
                    break;
                case '01':
                    // success with warning
                    return $er[2];
                    break;
                case '02':
                    // no data found
                default:
                    // error
                    return $er[2];
            }
        }
        return true;
    }
}
return 'modDatabaseTableOptimizeDatabaseProcessor';
