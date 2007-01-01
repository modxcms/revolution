<?php
/*
 * OpenExpedio (xPDO)
 * Copyright (C) 2006 Jason Coward <xpdo@opengeek.com>
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * The MySQL implementation of the xPDOManager class.
 * 
 * @package xpdo.om.mysql
 */

/**
 * Provides data source management for an xPDO instance.
 * 
 * These are utility functions that only need to be loaded under special
 * circumstances, such as creating tables, adding indexes, altering table
 * structures, etc.  xPDOManager class implementations are specific to a
 * database driver and this instance is implemented for MySQL.
 * 
 * @package xpdo.om.mysql
 */
class xPDOManager {
    /**
     * @var xPDO A reference to the XPDO instance using this manager.
     * @access public
     */
    var $xpdo= null;
    /**
     * @var xPDOGenerator The generator class for forward and reverse
     * engineering tasks (loaded only on demand).
     */
    var $generator= null;
    /**
     * @var xPDOTransport The data transport class for migrating data.
     */
    var $transport= null;

    var $action; // legacy action directive

    /**
     * Get a xPDOManager instance.
     * 
     * @param object $xpdo A reference to a specific modDataSource instance.
     */
    function xPDOManager(& $xpdo) {
        if ($xpdo !== null && $xpdo instanceof xPDO) {
            $this->xpdo= & $xpdo;
        }
        global $action;
        $this->action= $action; // set action directive
    }

    /**
     * Creates the physical data container represented by a data source
     */
    function createSourceContainer($dsn, $username= '', $password= '', $containerOptions= null) {
        $created= false;
        if (XPDO_ATTR_CREATE_TABLES) {
            if ($dsnArray= xPDO :: parseDSN($dsn)) {
                $sql= 'CREATE DATABASE `' . $dsnArray['dbname'] . '`';
                if ($conn= mysql_connect($dsnArray['host'], $username, $password, true)) {
                    if (!$rt= @ mysql_select_db($dsnArray['dbname'], $conn)) {
                        @ mysql_query($sql, $conn);
                        $created= mysql_errno($conn) ? false : true;
                    }
                }
            }
        }
        return $created;
    }

    /**
     * Drops a physical data container, if it exists.
     * 
     * @param string $dsn Represents the database connection string.
     * @param string $username Database username with priveleges to drop tables.
     * @param string $password Database user password.
     * @return int Returns 1 on successful drop, 0 on failure, and -1 if the db
     * does not exist.
     */
    function removeSourceContainer() {
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
    function createObjectContainer($className) {
        $created= false;
        if ($className= $this->xpdo->loadClass($className)) {
            if ($instance= new $className ($this->xpdo)) {
                $tableName= $instance->_table;
                $exists= $this->xpdo->query("SELECT COUNT(*) FROM {$tableName}");
                if (is_object($exists) && $exists->fetchAll()) {
                    return true;
                }
                $tableType= $instance->_tableType;
                $pk= $this->xpdo->getPK($className);
                $pktype= $this->xpdo->getPKType($className);
                $fulltextIndexes= array ();
                $uniqueIndexes= array ();
                $indexes= array ();
                $sql= 'CREATE TABLE ' . $tableName . ' (';
                foreach ($instance->_fieldMeta as $key => $meta) {
                    $precision= isset ($meta['precision']) ? '(' . $meta['precision'] . ')' : '';
                    $null= (isset ($meta['null']) && $meta['null'] == 'false') ? ' NOT NULL' : ' NULL';
                    if (isset ($instance->_fields[$key]) && $instance->_fields[$key] === 'NULL' || $instance->_fields[$key] === 'CURRENT_TIMESTAMP') {
                        $default= ' DEFAULT ' . $instance->_fields[$key];
                    } else {
                        $default= isset ($instance->_fields[$key]) ? ' DEFAULT \'' . $instance->_fields[$key] . '\'' : '';
                    }
                    $extra= (isset ($meta['index']) && $meta['index'] == 'pk' && !is_array($pk) && $pktype == 'integer' && isset ($meta['generated']) && $meta['generated'] == 'native') ? ' AUTO_INCREMENT' : '';
                    if (empty ($extra) && isset ($meta['extra'])) {
                        $extra= ' ' . $meta['extra'];
                    }
                    $attributes= (isset ($meta['attributes'])) ? ' ' . $meta['attributes'] : '';
                    if (strpos(strtolower($attributes), 'unsigned') !== false) {
                        $sql .= '`' . $key . '` ' . $meta['dbtype'] . $precision . $attributes . $null . $default . $extra . ',';
                    } else {
                        $sql .= '`' . $key . '` ' . $meta['dbtype'] . $precision . $null . $default . $attributes . $extra . ',';
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
                if (($created= $this->xpdo->exec($sql)) === false) {
                    $this->xpdo->_log(XPDO_LOG_LEVEL_FATAL, 'Could not create table ' . $tableName . "\nSQL: {$sql}\n" . print_r($this->xpdo->errorInfo(), true));
                } else {
                    $this->xpdo->_log(XPDO_LOG_LEVEL_INFO, 'Created table' . $tableName . "\nSQL: {$sql}\n");
                    $created= true;
                }
            }
        }
        return $created;
    }

    /**
     * Gets an XML schema parser / generator for this manager instance.
     * 
     * @return xPDOGenerator A generator class for this manager.
     */
    function getGenerator() {
        if ($this->generator === null || !is_a($this->generator, 'xPDOGenerator')) {
            if (!isset($this->xpdo->config['xPDOGenerator.'.$this->xpdo->config['dbtype'].'.class']) || !$generatorClass= $this->xpdo->loadClass($this->xpdo->config['xPDOGenerator.'.$this->xpdo->config['dbtype'].'.class'], XPDO_CORE_PATH, true, true)) {
                $generatorClass= $this->xpdo->loadClass($this->xpdo->config['dbtype'] . '.xPDOGenerator', '', true, true);
            }
            if ($generatorClass) {
                $this->generator= new $generatorClass ($this);
            } 
            if ($this->generator === null || !is_a($this->generator, 'xPDOGenerator')) {
                $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could not load xPDOGenerator [{$generatorClass}] class.");
            }
        }
        return $this->generator;
    }

    /**
     * Gets a data transport mechanism for this xPDOManager instance. 
     */
    function getTransport() {
        if ($this->transport === null || !is_a($this->transport, 'xPDOTransport')) {
            if (!isset($this->xpdo->config['xPDOTransport.class']) || !$transportClass= $this->xpdo->loadClass($this->xpdo->config['xPDOTransport.class'], XPDO_CORE_PATH, true, true)) { 
                $transportClass= $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
            }
            if ($transportClass) {
                $this->transport= new $transportClass ($this);
            } 
            if ($this->transport === null || !is_a($this->transport, 'xPDOTransport')) {
                $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could not load xPDOTransport [{$transportClass}] class.");
            }
        }
        return $this->transport;
    }
}
