<?php
/*
 * Copyright 2010-2015 by MODX, LLC.
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
require_once (dirname(__DIR__) . '/xpdomanager.class.php');

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
    public function createSourceContainer($dsnArray = null, $username= null, $password= null, $containerOptions= array ()) {
        $created = false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, 'SQLite does not support source container creation');
            if ($dsnArray === null) $dsnArray = xPDO::parseDSN($this->xpdo->getOption('dsn'));
            if (is_array($dsnArray)) {
                try {
                    $dbfile = $dsnArray['dbname'];
                    $created = !file_exists($dbfile);
                } catch (Exception $e) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error creating source container: " . $e->getMessage());
                }
            }
        }
        return $created;
    }

    public function removeSourceContainer($dsnArray = null, $username= null, $password= null) {
        $removed= false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            if ($dsnArray === null) $dsnArray = xPDO::parseDSN($this->xpdo->getOption('dsn'));
            if (is_array($dsnArray)) {
                try {
                    $dbfile = $dsnArray['dbname'];
                    if (file_exists($dbfile)) {
                        $removed = unlink($dbfile);
                        if (!$removed) {
                            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not remove source container");
                        }
                    } else {
                        $removed= true;
                    }
                } catch (Exception $e) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not remove source container: " . $e->getMessage());
                }
            }
        }
        return $removed;
    }

    public function removeObjectContainer($className) {
        $removed= false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
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
        }
        return $removed;
    }

    public function createObjectContainer($className) {
        $created= false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            $instance= $this->xpdo->newObject($className);
            if ($instance) {
                $tableName= $this->xpdo->getTableName($className);
                $existsStmt = $this->xpdo->query("SELECT COUNT(*) FROM {$tableName}");
                if ($existsStmt && $existsStmt->fetchAll()) {
                    return true;
                }
                $tableMeta= $this->xpdo->getTableMeta($className);
                $sql= 'CREATE TABLE ' . $tableName . ' (';
                $fieldMeta = $this->xpdo->getFieldMeta($className, true);
                $nativeGen = false;
                $columns = array();
                foreach ($fieldMeta as $key => $meta) {
                    $columns[] = $this->getColumnDef($className, $key, $meta);
                    if (array_key_exists('generated', $meta) && $meta['generated'] == 'native') $nativeGen = true;
                }
                $sql .= implode(', ', $columns);
                $indexes = $this->xpdo->getIndexMeta($className);
                $createIndices = array();
                $tableConstraints = array();
                if (!empty ($indexes)) {
                    foreach ($indexes as $indexkey => $indexdef) {
                        $indexkey = $this->xpdo->literal($instance->_table) . '_' . $indexkey;
                        $indexType = ($indexdef['primary'] ? 'PRIMARY KEY' : ($indexdef['unique'] ? 'UNIQUE' : 'INDEX'));
                        $index = $indexdef['columns'];
                        if (is_array($index)) {
                            $indexset= array ();
                            foreach ($index as $indexmember => $indexmemberdetails) {
                                $indexMemberDetails = $this->xpdo->escape($indexmember);
                                $indexset[]= $indexMemberDetails;
                            }
                            $indexset= implode(',', $indexset);
                            if (!empty($indexset)) {
                                switch ($indexType) {
                                    case 'UNIQUE':
                                        $createIndices[$indexkey] = "CREATE UNIQUE INDEX {$this->xpdo->escape($indexkey)} ON {$tableName} ({$indexset})";
                                        break;
                                    case 'INDEX':
                                        $createIndices[$indexkey] = "CREATE INDEX {$this->xpdo->escape($indexkey)} ON {$tableName} ({$indexset})";
                                        break;
                                    case 'PRIMARY KEY':
                                        if ($nativeGen) break;
                                        $tableConstraints[]= "{$indexType} ({$indexset})";
                                        break;
                                    default:
                                        $tableConstraints[]= "CONSTRAINT {$this->xpdo->escape($indexkey)} {$indexType} ({$indexset})";
                                        break;
                                }
                            }
                        }
                    }
                }
                if (!empty($tableConstraints)) {
                    $sql .= ', ' . implode(', ', $tableConstraints);
                }
                $sql .= ")";
                $created= $this->xpdo->exec($sql);
                if ($created === false && $this->xpdo->errorCode() !== '' && $this->xpdo->errorCode() !== PDO::ERR_NONE) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not create table ' . $tableName . "\nSQL: {$sql}\nERROR: " . print_r($this->xpdo->errorInfo(), true));
                } else {
                    $created= true;
                    $this->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Created table ' . $tableName . "\nSQL: {$sql}\n");
                }
                if ($created === true && !empty($createIndices)) {
                    foreach ($createIndices as $createIndexKey => $createIndex) {
                        $indexCreated = $this->xpdo->exec($createIndex);
                        if ($indexCreated === false && $this->xpdo->errorCode() !== '' && $this->xpdo->errorCode() !== PDO::ERR_NONE) {
                            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not create index {$createIndexKey}: {$createIndex} " . print_r($this->xpdo->errorInfo(), true));
                        } else {
                            $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Created index {$createIndexKey} on {$tableName}: {$createIndex}");
                        }
                    }
                }
            }
        }
        return $created;
    }

    public function alterObjectContainer($className, array $options = array()) {
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            // TODO: Implement alterObjectContainer() method.
        }
    }

    public function addConstraint($class, $name, array $options = array()) {
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            // TODO: Implement addConstraint() method.
        }
    }

    public function addField($class, $name, array $options = array()) {
        $result = false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            $className = $this->xpdo->loadClass($class);
            if ($className) {
                $meta = $this->xpdo->getFieldMeta($className, true);
                if (is_array($meta) && array_key_exists($name, $meta)) {
                    $colDef = $this->getColumnDef($className, $name, $meta[$name]);
                    if (!empty($colDef)) {
                        $sql = "ALTER TABLE {$this->xpdo->getTableName($className)} ADD COLUMN {$colDef}";
                        if ($this->xpdo->exec($sql) !== false) {
                            $result = true;
                        } else {
                            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error adding field {$class}->{$name}: " . print_r($this->xpdo->errorInfo(), true), '', __METHOD__, __FILE__, __LINE__);
                        }
                    } else {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error adding field {$class}->{$name}: Could not get column definition");
                    }
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error adding field {$class}->{$name}: No metadata defined");
                }
            }
        }
        return $result;
    }

    public function addIndex($class, $name, array $options = array()) {
        $result = false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            $className = $this->xpdo->loadClass($class);
            if ($className) {
                $meta = $this->xpdo->getIndexMeta($className);
                if (is_array($meta) && array_key_exists($name, $meta)) {
                    $indexType = ($meta[$name]['unique'] ? 'UNIQUE INDEX' : 'INDEX');
                    $idxDef = $this->getIndexDef($className, $name, $meta[$name]);
                    if (!empty($idxDef)) {
                        $sql = "CREATE {$indexType} ON {$this->xpdo->getTableName($className)} ({$idxDef})";
                        if ($this->xpdo->exec($sql) !== false) {
                            $result = true;
                        } else {
                            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error adding index {$name} to {$class}: " . print_r($this->xpdo->errorInfo(), true), '', __METHOD__, __FILE__, __LINE__);
                        }
                    } else {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error adding index {$name} to {$class}: Could not get index definition");
                    }
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error adding index {$name} to {$class}: No metadata defined");
                }
            }
        }
        return $result;
    }

    public function alterField($class, $name, array $options = array()) {
        $result = false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            // TODO: Implement alterField() method somehow, no support in sqlite for altering existing columns
        }
        return $result;
    }

    public function removeConstraint($class, $name, array $options = array()) {
        $result = false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            // TODO: Implement removeConstraint() method.
        }
        return $result;
    }

    public function removeField($class, $name, array $options = array()) {
        $result = false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            // TODO: Implement removeField() method somehow, no support in sqlite for dropping existing columns
        }
        return $result;
    }

    public function removeIndex($class, $name, array $options = array()) {
        $result = false;
        if ($this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            $className = $this->xpdo->loadClass($class);
            if ($className) {
                $sql = "DROP INDEX {$this->xpdo->escape($name)}";
                if ($this->xpdo->exec($sql) !== false) {
                    $result = true;
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error removing index {$name} from {$class}: " . print_r($this->xpdo->errorInfo(), true), '', __METHOD__, __FILE__, __LINE__);
                }
            }
        }
        return $result;
    }

    protected function getColumnDef($class, $name, $meta, array $options = array()) {
        $pk = $this->xpdo->getPK($class);
        $pktype = $this->xpdo->getPKType($class);
        $dbtype= strtoupper($meta['dbtype']);
        $precision= isset ($meta['precision']) && !preg_match('/ENUM/i', $dbtype) ? '(' . $meta['precision'] . ')' : '';
        if (preg_match('/ENUM/i', $dbtype)) {
            $dbtype= 'CHAR';
        }
        $notNull= !isset ($meta['null'])
            ? false
            : ($meta['null'] === 'false' || empty($meta['null']));
        $null= $notNull ? ' NOT NULL' : ' NULL';
        $extra = '';
        if (isset ($meta['index']) && $meta['index'] == 'pk' && !is_array($pk) && $pktype == 'integer' && isset ($meta['generated']) && $meta['generated'] == 'native') {
            $extra= ' PRIMARY KEY AUTOINCREMENT';
            $options['nativeGen'] = true;
            $null= '';
        }
        if (empty ($extra) && isset ($meta['extra'])) {
            $extra= ' ' . $meta['extra'];
        }
        $default= '';
        if (array_key_exists('default', $meta)) {
            $defaultVal= $meta['default'];
            if ($defaultVal === null || strtoupper($defaultVal) === 'NULL' || in_array($this->xpdo->driver->getPhpType($dbtype), array('integer', 'float')) || (in_array($meta['phptype'], array('datetime', 'date', 'timestamp', 'time')) && in_array($defaultVal, array_merge($this->xpdo->driver->_currentTimestamps, $this->xpdo->driver->_currentDates, $this->xpdo->driver->_currentTimes)))) {
                $default= ' DEFAULT ' . $defaultVal;
            } else {
                $default= ' DEFAULT \'' . $defaultVal . '\'';
            }
        }
        $attributes= (isset ($meta['attributes'])) ? ' ' . $meta['attributes'] : '';
        if (strpos(strtolower($attributes), 'unsigned') !== false) {
            $result = $this->xpdo->escape($name) . ' ' . $dbtype . $precision . $attributes . $null . $default . $extra;
        } else {
            $result = $this->xpdo->escape($name) . ' ' . $dbtype . $precision . $null . $default . $attributes . $extra;
        }
        return $result;
    }

    protected function getIndexDef($class, $name, $meta, array $options = array()) {
        $result = '';
        $index = isset($meta['columns']) ? $meta['columns'] : null;
        if (is_array($index)) {
            $indexset= array ();
            foreach ($index as $indexmember => $indexmemberdetails) {
                $indexMemberDetails = $this->xpdo->escape($indexmember);
                $indexset[]= $indexMemberDetails;
            }
            $result= implode(',', $indexset);
        }
        return $result;
    }
}
