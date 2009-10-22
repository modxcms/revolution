<?php
/*
 * Copyright 2006, 2007, 2008, 2009 by  Jason Coward <xpdo@opengeek.com>
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
 * The MySQL implementation of the xPDOManager class.
 *
 * @package xpdo
 * @subpackage om.mysql
 */

/**
 * Include the parent {@link xPDOManager} class.
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/xpdomanager.class.php');

/**
 * Provides MySQL data source management for an xPDO instance.
 *
 * These are utility functions that only need to be loaded under special
 * circumstances, such as creating tables, adding indexes, altering table
 * structures, etc.  xPDOManager class implementations are specific to a
 * database driver and this instance is implemented for MySQL.
 *
 * @package xpdo
 * @subpackage om.mysql
 */
class xPDOManager_mysql extends xPDOManager {
    /**
     * Get a xPDOManager instance.
     *
     * @param object $xpdo A reference to a specific modDataSource instance.
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->dbtypes['integer']= array('INT','INTEGER','TINYINT','BOOLEAN','SMALLINT','MEDIUMINT','BIGINT');
        $this->dbtypes['boolean']= array('BOOLEAN','BOOL');
        $this->dbtypes['float']= array('DECIMAL','DEC','NUMERIC','FLOAT','DOUBLE','DOUBLE PRECISION','REAL');
        $this->dbtypes['string']= array('CHAR','VARCHAR','BINARY','VARBINARY','TINYTEXT','TEXT','MEDIUMTEXT','LONGTEXT','ENUM','SET','TIME','YEAR');
        $this->dbtypes['timestamp']= array('TIMESTAMP');
        $this->dbtypes['datetime']= array('DATETIME');
        $this->dbtypes['date']= array('DATE');
        $this->dbtypes['binary']= array('TINYBLOB','BLOB','MEDIUMBLOB','LONGBLOB');
        $this->dbtypes['bit']= array('BIT');
    }

    /**
     * Creates the physical data container represented by a data source.
     *
     * @param array $dsnArray An array of xPDO configuration properties.
     * @param string $username Database username with privileges to create tables.
     * @param string $password Database user password.
     * @param array $containerOptions An array of options for controlling the creation of the container.
     * @return boolean True only if the database is created successfully.
     */
    public function createSourceContainer($dsnArray, $username= '', $password= '', $containerOptions= array ()) {
        $created= false;
        if (is_array($dsnArray)) {
            $sql= 'CREATE DATABASE `' . $dsnArray['dbname'] . '`';
            if (isset ($containerOptions['collation']) && isset ($containerOptions['charset'])) {
                $sql.= ' CHARACTER SET ' . $containerOptions['charset'];
                $sql.= ' COLLATE ' . $containerOptions['collation'];
            }
            if ($conn= mysql_connect($dsnArray['host'], $username, $password, true)) {
                if (!$rt= @ mysql_select_db($dsnArray['dbname'], $conn)) {
                    @ mysql_query($sql, $conn);
                $errorNo= @ mysql_errno($conn);
                $created= $errorNo ? false : true;
                }
            }
        }
        return $created;
    }

    /**
     * Drops a physical data container, if it exists.
     *
     * @param string $dsn Represents the database connection string.
     * @param string $username Database username with privileges to drop tables.
     * @param string $password Database user password.
     * @return int Returns 1 on successful drop, 0 on failure, and -1 if the db
     * does not exist.
     */
    public function removeSourceContainer() {
        $removed= 0;
        if ($dsnArray= & $this->xpdo->config) {
            $sql= 'DROP DATABASE `' . $dsnArray['dbname'] . '`';
            if ($conn= @ mysql_connect($dsnArray['host'], $dsnArray['username'], $dsnArray['password'], true)) {
                if (@ mysql_select_db($dsnArray['dbname'], $conn)) {
                    if (@ mysql_query($sql, $conn)) {
                        $removed= 1;
                    }
                } else {
                    $removed= -1;
                }
            }
        }
        return $removed;
    }


    /**
     * Drops a table, if it exists.
     *
     * @param string $className The object table to drop.
     * @return int Returns 1 on successful drop, 0 on failure, and -1 if the table
     * does not exist.
     */
    public function removeObjectContainer($className) {
        $removed= 0;
        if ($instance= $this->xpdo->newObject($className)) {
            $sql= 'DROP TABLE ' . $this->xpdo->getTableName($className);
            $removed= $this->xpdo->exec($sql);
            if (!$removed && $this->xpdo->errorCode() !== '' && $this->xpdo->errorCode() !== PDO::ERR_NONE) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not drop table ' . $className . "\nSQL: {$sql}\nERROR: " . print_r($this->xpdo->pdo->errorInfo(), true));
            } else {
                $removed= true;
                $this->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Dropped table' . $className . "\nSQL: {$sql}\n");
            }
        }
        return $removed;
    }

    /**
     * Creates the container for a persistent data object.  In this
     * implementation, a source container is a synonym for a MySQL database
     * table.
     *
     * @todo Add more robust index support.
     * @todo Add constraint support.
     *
     * @param string $className The class of object to create a source container
     * for.
     */
    public function createObjectContainer($className) {
        $created= false;
        if ($instance= $this->xpdo->newObject($className)) {
            $tableName= $this->xpdo->getTableName($className);
            $existsStmt = $this->xpdo->prepare("SELECT COUNT(*) FROM {$tableName}");
            $exists = $existsStmt->execute();
            if ($exists && $existsStmt->fetchAll()) {
                return true;
            }
            $tableMeta= $this->xpdo->getTableMeta($className);
            $tableType= isset($tableMeta['engine']) ? $tableMeta['engine'] : 'MyISAM';
            $numerics= array_merge($this->dbtypes['integer'], $this->dbtypes['boolean'], $this->dbtypes['float']);
            $datetimeStrings= array_merge($this->dbtypes['timestamp'], $this->dbtypes['datetime']);
            $dateStrings= $this->dbtypes['date'];
            $pk= $this->xpdo->getPK($className);
            $pktype= $this->xpdo->getPKType($className);
            $fulltextIndexes= array ();
            $uniqueIndexes= array ();
            $indexes= array ();
            $lobs= array ('TEXT', 'BLOB');
            $lobsPattern= '/(' . implode('|', $lobs) . ')/';
            $sql= 'CREATE TABLE ' . $tableName . ' (';
            $fieldMeta = $this->xpdo->getFieldMeta($className);
            while (list($key, $meta)= each($fieldMeta)) {
                $dbtype= strtoupper($meta['dbtype']);
                $precision= isset ($meta['precision']) ? '(' . $meta['precision'] . ')' : '';
                $notNull= !isset ($meta['null'])
                    ? false
                    : ($meta['null'] === 'false' || empty($meta['null']));
                $null= $notNull ? ' NOT NULL' : ' NULL';
                $extra= (isset ($meta['index']) && $meta['index'] == 'pk' && !is_array($pk) && $pktype == 'integer' && isset ($meta['generated']) && $meta['generated'] == 'native') ? ' AUTO_INCREMENT' : '';
                if (empty ($extra) && isset ($meta['extra'])) {
                    $extra= ' ' . $meta['extra'];
                }
                $default= '';
                if (isset ($meta['default']) && !preg_match($lobsPattern, $dbtype)) {
                    $defaultVal= $meta['default'];
                    if (($defaultVal === null || strtoupper($defaultVal) === 'NULL') || (in_array($dbtype, $datetimeStrings) && $defaultVal === 'CURRENT_TIMESTAMP')) {
                        $default= ' DEFAULT ' . $defaultVal;
                    } else {
                        $default= ' DEFAULT \'' . $defaultVal . '\'';
                    }
                }
                $attributes= (isset ($meta['attributes'])) ? ' ' . $meta['attributes'] : '';
                if (strpos(strtolower($attributes), 'unsigned') !== false) {
                    $sql .= '`' . $key . '` ' . $dbtype . $precision . $attributes . $null . $default . $extra . ',';
                } else {
                    $sql .= '`' . $key . '` ' . $dbtype . $precision . $null . $default . $attributes . $extra . ',';
                }
                if (isset ($meta['index']) && $meta['index'] !== 'pk') {
                    if ($meta['index'] === 'fulltext') {
                        if (isset ($meta['indexgrp'])) {
                            $fulltextIndexes[$meta['indexgrp']][]= $key;
                        } else {
                            $fulltextIndexes[$key]= $key;
                        }
                    }
                    elseif ($meta['index'] === 'unique') {
                        if (isset ($meta['indexgrp'])) {
                            $uniqueIndexes[$meta['indexgrp']][]= $key;
                        } else {
                            $uniqueIndexes[$key]= $key;
                        }
                    }
                    elseif ($meta['index'] === 'fk') {
                        if (isset ($meta['indexgrp'])) {
                            $indexes[$meta['indexgrp']][]= $key;
                        } else {
                            $indexes[$key]= $key;
                        }
                    } else {
                        if (isset ($meta['indexgrp'])) {
                            $indexes[$meta['indexgrp']][]= $key;
                        } else {
                            $indexes[$key]= $key;
                        }
                    }
                }
            }
            $sql= substr($sql, 0, strlen($sql) - 1);
            if (is_array($pk)) {
                $pkarray= array ();
                foreach ($pk as $k) {
                    $pkarray[]= '`' . $k . '`';
                }
                $pk= implode(',', $pkarray);
            }
            elseif ($pk) {
                $pk= '`' . $pk . '`';
            }
            if ($pk)
                $sql .= ', PRIMARY KEY (' . $pk . ')';
            if (!empty ($indexes)) {
                foreach ($indexes as $indexkey => $index) {
                    if (is_array($index)) {
                        $indexset= array ();
                        foreach ($index as $indexmember) {
                            $indexset[]= "`{$indexmember}`";
                        }
                        $indexset= implode(',', $indexset);
                    } else {
                        $indexset= "`{$indexkey}`";
                    }
                    $sql .= ", INDEX `{$indexkey}` ({$indexset})";
                }
            }
            if (!empty ($uniqueIndexes)) {
                foreach ($uniqueIndexes as $indexkey => $index) {
                    if (is_array($index)) {
                        $indexset= array ();
                        foreach ($index as $indexmember) {
                            $indexset[]= "`{$indexmember}`";
                        }
                        $indexset= implode(',', $indexset);
                    } else {
                        $indexset= $indexkey;
                    }
                    $sql .= ", UNIQUE INDEX `{$indexkey}` ({$indexset})";
                }
            }
            if (!empty ($fulltextIndexes)) {
                foreach ($fulltextIndexes as $indexkey => $index) {
                    if (is_array($index)) {
                        $indexset= array ();
                        foreach ($index as $indexmember) {
                            $indexset[]= "`{$indexmember}`";
                        }
                        $indexset= implode(',', $indexset);
                    } else {
                        $indexset= $indexkey;
                    }
                    $sql .= ", FULLTEXT INDEX `{$indexkey}` ({$indexset})";
                }
            }
            $sql .= ") TYPE={$tableType}";
            $created= $this->xpdo->exec($sql);
            if (!$created && $this->xpdo->errorCode() !== '' && $this->xpdo->errorCode() !== PDO::ERR_NONE) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not create table ' . $tableName . "\nSQL: {$sql}\nERROR: " . print_r($this->xpdo->errorInfo(), true));
            } else {
                $created= true;
                $this->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Created table' . $tableName . "\nSQL: {$sql}\n");
            }
        }
        return $created;
    }
}
