<?php
/*
 * Copyright 2006-2010 by  Jason Coward <xpdo@opengeek.com>
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * The SQLite implementation of the xPDOManager class.
 *
 * @package xpdo
 * @subpackage om.sqlite
 */

/**
 * Include the parent {@link xPDOManager} class.
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/xpdomanager.class.php');

/**
 * Provides SQLite data source management for an xPDO instance.
 *
 * These are utility functions that only need to be loaded under special
 * circumstances, such as creating tables, adding indexes, altering table
 * structures, etc.  xPDOManager class implementations are specific to a
 * database driver and this instance is implemented for SQLite.
 *
 * @package xpdo
 * @subpackage om.sqlite
 */
class xPDOManager_sqlite extends xPDOManager {
    /**
     * Get a SQLite xPDOManager instance.
     *
     * @param object $xpdo A reference to a specific modDataSource instance.
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->dbtypes['integer']= array('/INT/i');
        $this->dbtypes['string']= array('/CHAR/i','/CLOB/i','/TEXT/i', '/ENUM/i');
        $this->dbtypes['float']= array('/REAL/i','/FLOA/i','/DOUB/i');
        $this->dbtypes['datetime']= array('/TIMESTAMP/i','/DATE/i');
        $this->dbtypes['binary']= array('/BLOB/i');
    }

    public function createSourceContainer($dsnArray = null, $username= null, $password= null, $containerOptions= array ()) {
        $created = false;
        $this->xpdo->log(xPDO::LOG_LEVEL_WARN, 'SQLite does not support source container creation');
        if ($dsnArray === null) $dsnArray = xPDO::parseDSN($this->xpdo->getOption('dsn'));
        if (is_array($dsnArray)) {
            try {
                $dbfile = $dsnArray['dbname'];
                $created = !file_exists($dbfile);
            } catch (Exception $e) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error creating source container: " . $pe->getMessage());
            }
        }
        return $created;
    }

    public function removeSourceContainer($dsnArray = null, $username= null, $password= null) {
        $removed= false;
        if ($dsnArray === null) $dsnArray = xPDO::parseDSN($this->xpdo->getOption('dsn'));
        if (is_array($dsnArray)) {
            try {
                $dbfile = $dsnArray['dbname'];
                if (file_exists($dbfile)) {
                    $removed = unlink($dbfile);
                    if (!$removed) {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not remove source container");
                    }
                }
            } catch (Exception $e) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not remove source container: " . $pe->getMessage());
            }
        }
        return $removed;
    }

    public function removeObjectContainer($className) {
        $removed= false;
        $instance= $this->xpdo->newObject($className);
        if ($instance) {
            $sql= 'DROP TABLE ' . $this->xpdo->getTableName($className);
            $removed= $this->xpdo->exec($sql);
            if ($removed === false && $this->xpdo->errorCode() !== '' && $this->xpdo->errorCode() !== PDO::ERR_NONE) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not drop table ' . $className . "\nSQL: {$sql}\nERROR: " . print_r($this->xpdo->pdo->errorInfo(), true));
            } else {
                $removed= true;
                $this->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Dropped table' . $className . "\nSQL: {$sql}\n");
            }
        }
        return $removed;
    }

    public function createObjectContainer($className) {
        $created= false;
        $instance= $this->xpdo->newObject($className);
        if ($instance) {
            $tableName= $this->xpdo->getTableName($className);
            $existsStmt = $this->xpdo->query("SELECT COUNT(*) FROM {$tableName}");
            if ($existsStmt && $existsStmt->fetchAll()) {
                return true;
            }
            $tableMeta= $this->xpdo->getTableMeta($className);
            $pk= $this->xpdo->getPK($className);
            $pktype= $this->xpdo->getPKType($className);
            $sql= 'CREATE TABLE ' . $tableName . ' (';
            $fieldMeta = $this->xpdo->getFieldMeta($className);
            while (list($key, $meta)= each($fieldMeta)) {
                $dbtype= strtoupper($meta['dbtype']);
                $precision= isset ($meta['precision']) && !preg_match('/ENUM/i', $dbtype) ? '(' . $meta['precision'] . ')' : '';
                if (preg_match('/ENUM/i', $dbtype)) {
                    $dbtype= 'CHAR';
                }
                $notNull= !isset ($meta['null'])
                    ? false
                    : ($meta['null'] === 'false' || empty($meta['null']));
                $null= $notNull ? ' NOT NULL' : ' NULL';
                $extra= (isset ($meta['index']) && $meta['index'] == 'pk' && !is_array($pk) && $pktype == 'integer' && isset ($meta['generated']) && $meta['generated'] == 'native') ? ' PRIMARY KEY AUTOINCREMENT' : '';
                if (empty ($extra) && isset ($meta['extra'])) {
                    $extra= ' ' . $meta['extra'];
                }
                $default= '';
                if (array_key_exists('default', $meta)) {
                    $defaultVal= $meta['default'];
                    if ($defaultVal === null || strtoupper($defaultVal) === 'NULL' || in_array($this->getPHPType($dbtype), array('integer', 'float')) || (in_array($meta['phptype'], array('datetime', 'date', 'timestamp', 'time')) && in_array($defaultVal, array_merge($instance->_currentTimestamps, $instance->_currentDates, $instance->_currentTimes)))) {
                        $default= ' DEFAULT ' . $defaultVal;
                    } else {
                        $default= ' DEFAULT \'' . $defaultVal . '\'';
                    }
                }
                $attributes= (isset ($meta['attributes'])) ? ' ' . $meta['attributes'] : '';
                if (strpos(strtolower($attributes), 'unsigned') !== false) {
                    $sql .= $this->xpdo->escape($key) . ' ' . $dbtype . $precision . $attributes . $null . $default . $extra . ',';
                } else {
                    $sql .= $this->xpdo->escape($key) . ' ' . $dbtype . $precision . $null . $default . $attributes . $extra . ',';
                }
            }
            $sql= substr($sql, 0, strlen($sql) - 1);
            if (is_array($pk)) {
                $pkarray= array ();
                foreach ($pk as $k) {
                    $pkarray[]= $this->xpdo->escape($k);
                }
                $pk= implode(',', $pkarray);
                $sql .= ', PRIMARY KEY (' . $pk . ')';
            }
            elseif ($pk) {
                $pk= $this->xpdo->escape($pk);
            }
            if (!empty ($indexes)) {
                foreach ($indexes as $indexkey => $index) {
                    if (is_array($index)) {
                        $indexset= array ();
                        foreach ($index as $indexmember) {
                            $indexset[]= $this->xpdo->escape($indexmember);
                        }
                        $indexset= implode(',', $indexset);
                    } else {
                        $indexset= $this->xpdo->escape($indexkey);
                    }
                    $sql .= ", INDEX " . $this->xpdo->escape($indexkey) . " ({$indexset})";
                }
            }
            if (!empty ($uniqueIndexes)) {
                foreach ($uniqueIndexes as $indexkey => $index) {
                    if (is_array($index)) {
                        $indexset= array ();
                        foreach ($index as $indexmember) {
                            $indexset[]= $this->xpdo->escape($indexmember);
                        }
                        $indexset= implode(',', $indexset);
                    } else {
                        $indexset= $this->xpdo->escape($indexkey);
                    }
                    $sql .= ", UNIQUE INDEX {$indexkey} ({$indexset})";
                }
            }
            if (!empty ($fulltextIndexes)) {
                foreach ($fulltextIndexes as $indexkey => $index) {
                    if (is_array($index)) {
                        $indexset= array ();
                        foreach ($index as $indexmember) {
                            $indexset[]= $this->xpdo->escape($indexmember);
                        }
                        $indexset= implode(',', $indexset);
                    } else {
                        $indexset= $this->xpdo->escape($indexkey);
                    }
                    $sql .= ", FULLTEXT INDEX {$indexkey} ({$indexset})";
                }
            }
            $sql .= ")";
            $created= $this->xpdo->exec($sql);
            if ($created === false && $this->xpdo->errorCode() !== '' && $this->xpdo->errorCode() !== PDO::ERR_NONE) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not create table ' . $tableName . "\nSQL: {$sql}\nERROR: " . print_r($this->xpdo->errorInfo(), true));
            } else {
                $created= true;
                $this->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Created table ' . $tableName . "\nSQL: {$sql}\n");
            }
        }
        return $created;
    }
}
