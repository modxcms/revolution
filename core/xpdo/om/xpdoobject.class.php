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
 * The base persistent xPDO object classes.
 *
 * This file contains the base persistent object classes, which your user-
 * defined classes will extend when implementing an xPDO object model.
 *
 * @package xpdo
 * @subpackage om
 */

/**
 * The base persistent xPDO object class.
 *
 * This is the basis for the entire xPDO object model, and can also be used by a
 * class generator {@link xPDOGenerator}, ultimately allowing custom classes to
 * be user-defined in a web interface and framework-generated at runtime.
 *
 * @abstract This is an abstract class, and is not represented by an actual
 * table; it simply defines the member variables and functions needed for object
 * persistence.
 *
 * @package xpdo
 * @subpackage om
 */
class xPDOObject {
    /**
     * A convenience reference to the xPDO object.
     * @var xPDO
     * @access public
     */
    public $xpdo= null;

    /**
     * Name of the data source container the object belongs to.
     * @var string
     * @access public
     */
    public $container= null;

    /**
     * Names of the fields in the data table, fully-qualified with a table name.
     *
     * NOTE: For use in table joins to qualify fields with the same name.
     *
     * @var array
     * @access public
     */
    public $fieldNames= null;

    /**
     * The actual class name of an instance.
     * @var string
     */
    public $_class= null;

    /**
     * The package the class is a part of.
     * @var string
     */
    public $_package= null;

    /**
     * An alias for this instance of the class.
     * @var string
     */
    public $_alias= null;

    /**
     * The primary key field (or an array of primary key fields) for this object.
     * @var string|array
     * @access public
     */
    public $_pk= null;

    /**
     * The php native type of the primary key field.
     *
     * NOTE: Will be an array if multiple primary keys are specified for the object.
     *
     * @var string|array
     * @access public
     */
    public $_pktype= null;

    /**
     * Name of the actual table representing this class.
     * @var string
     * @access public
     */
    public $_table= null;

    /**
     * An array of meta data for the table.
     * @var string
     * @access public
     */
    public $_tableMeta= null;

    /**
     * An array of field names that have been modified.
     * @var array
     * @access public
     */
    public $_dirty= array ();

    /**
     * An array of field names that have not been loaded from the source.
     * @var array
     * @access public
     */
    public $_lazy= array ();

    /**
     * An array of key-value pairs representing the fields of the instance.
     * @var array
     * @access public
     */
    public $_fields= array ();

    /**
     * An array of metadata definitions for each field in the class.
     * @var array
     * @access public
     */
    public $_fieldMeta= array ();

    /**
     * An optional array of field aliases.
     * @var array
     */
    public $_fieldAliases= array();

    /**
     * An array of aggregate foreign key relationships for the class.
     * @var array
     * @access public
     */
    public $_aggregates= array ();

    /**
     * An array of composite foreign key relationships for the class.
     * @var array
     * @access public
     */
    public $_composites= array ();

    /**
     * An array of object instances related to this object instance.
     * @var array
     * @access public
     */
    public $_relatedObjects= array ();

    /**
     * A validator object responsible for this object instance.
     * @var xPDOValidator
     * @access public
     */
    public $_validator = null;

    /**
     * An array of validation rules for this object instance.
     * @var array
     * @access public
     */
    public $_validationRules = array();

    /**
     * An array of field names that have been already validated.
     * @var array
     * @access public
     */
    public $_validated= array ();

    /**
     * Indicates if the validation map has been loaded.
     * @var boolean
     * @access public
     */
    public $_validationLoaded= false;

    /**
     * Indicates if the instance is transient (and thus new).
     * @var boolean
     * @access public
     */
    public $_new= true;

    /**
     * Indicates the cacheability of the instance.
     * @var boolean
     */
    public $_cacheFlag= true;

    /**
     * A collection of various options that can be used on the instance.
     * @var array
     */
    public $_options= array();

    /**
     * Responsible for loading a result set from the database.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param xPDOCriteria $criteria A valid xPDOCriteria instance.
     * @return PDOStatement A reference to a PDOStatement representing the
     * result set.
     */
    public static function & _loadRows(& $xpdo, $className, $criteria) {
        $rows= null;
        if ($criteria->prepare()) {
            if ($xpdo->getDebug() === true) $xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Attempting to execute query using PDO statement object: " . print_r($criteria->sql, true) . print_r($criteria->bindings, true));
            $tstart= microtime(true);
            if (!$criteria->stmt->execute()) {
                $xpdo->queryTime += microtime(true) - $tstart;
                $xpdo->executedQueries++;
                $errorInfo= $criteria->stmt->errorInfo();
                $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error ' . $criteria->stmt->errorCode() . " executing statement: \n" . print_r($errorInfo, true));
                if (($errorInfo[1] == '1146' || $errorInfo[1] == '1') && $xpdo->getOption(xPDO::OPT_AUTO_CREATE_TABLES)) {
                    if ($xpdo->getManager() && $xpdo->manager->createObjectContainer($className)) {
                        $tstart= microtime(true);
                        if (!$criteria->stmt->execute()) {
                            $xpdo->queryTime += microtime(true) - $tstart;
                            $xpdo->executedQueries++;
                            $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error " . $criteria->stmt->errorCode() . " executing statement: \n" . print_r($criteria->stmt->errorInfo(), true));
                        } else {
                            $xpdo->queryTime += microtime(true) - $tstart;
                            $xpdo->executedQueries++;
                        }
                    } else {
                        $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error " . $xpdo->errorCode() . " attempting to create object container for class {$className}:\n" . print_r($xpdo->errorInfo(), true));
                    }
                }
            } else {
                $xpdo->queryTime += microtime(true) - $tstart;
                $xpdo->executedQueries++;
            }
            $rows= & $criteria->stmt;
        } else {
            $errorInfo = $xpdo->errorInfo();
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error preparing statement for query: {$criteria->sql} - " . print_r($errorInfo, true));
            if (($errorInfo[1] == '1146' || $errorInfo[1] == '1') && $xpdo->getOption(xPDO::OPT_AUTO_CREATE_TABLES)) {
                if ($xpdo->getManager() && $xpdo->manager->createObjectContainer($className)) {
                    if (!$criteria->prepare()) {
                        $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error preparing statement for query: {$criteria->sql} - " . print_r($errorInfo, true));
                    } else {
                        $tstart= microtime(true);
                        if (!$criteria->stmt->execute()) {
                            $xpdo->queryTime += microtime(true) - $tstart;
                            $xpdo->executedQueries++;
                            $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error " . $criteria->stmt->errorCode() . " executing statement: \n" . print_r($criteria->stmt->errorInfo(), true));
                        } else {
                            $xpdo->queryTime += microtime(true) - $tstart;
                            $xpdo->executedQueries++;
                        }
                    }
                } else {
                    $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error " . $xpdo->errorCode() . " attempting to create object container for class {$className}:\n" . print_r($xpdo->errorInfo(), true));
                }
            }
        }
        return $rows;
    }

    /**
     * Loads an instance from an associative array.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param xPDOQuery|string $criteria A valid xPDOQuery instance or relation alias.
     * @param array $row The associative array containing the instance data.
     * @return xPDOObject A new xPDOObject derivative representing a data row.
     */
    public static function _loadInstance(& $xpdo, $className, $criteria, $row) {
        $rowPrefix= '';
        if (is_object($criteria) && $criteria instanceof xPDOQuery) {
            $alias = $criteria->getAlias();
            $actualClass = $criteria->getClass();
        } elseif (is_string($criteria) && !empty($criteria)) {
            $alias = $criteria;
            $actualClass = $className;
        } else {
            $alias = $className;
            $actualClass= $className;
        }
        if (isset ($row["{$alias}_class_key"])) {
            $actualClass= $row["{$alias}_class_key"];
            $rowPrefix= $alias . '_';
        } elseif (isset($row["{$className}_class_key"])) {
            $actualClass= $row["{$className}_class_key"];
            $rowPrefix= $className . '_';
        } elseif (isset ($row['class_key'])) {
            $actualClass= $row['class_key'];
        }
        /** @var xPDOObject $instance */
        $instance= $xpdo->newObject($actualClass);
        if (is_object($instance) && $instance instanceof xPDOObject) {
            $pk = $xpdo->getPK($actualClass);
            if ($pk) {
                if (is_array($pk)) $pk = reset($pk);
                if (isset($row["{$alias}_{$pk}"])) {
                    $rowPrefix= $alias . '_';
                }
                elseif ($actualClass !== $className && $actualClass !== $alias && isset($row["{$actualClass}_{$pk}"])) {
                    $rowPrefix= $actualClass . '_';
                }
                elseif ($className !== $alias && isset($row["{$className}_{$pk}"])) {
                    $rowPrefix= $className . '_';
                }
            } elseif (strpos(strtolower(key($row)), strtolower($alias . '_')) === 0) {
                $rowPrefix= $alias . '_';
            } elseif (strpos(strtolower(key($row)), strtolower($className . '_')) === 0) {
                $rowPrefix= $className . '_';
            }
            $parentClass = $className;
            $isSubPackage = strpos($className,'.');
            if ($isSubPackage !== false) {
                $parentClass = substr($className,$isSubPackage+1);
            }
            if (!$instance instanceof $parentClass) {
                $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Instantiated a derived class {$actualClass} that is not a subclass of the requested class {$className}");
            }
            $instance->_lazy= $actualClass !== $className ? array_keys($xpdo->getFieldMeta($actualClass)) : array_keys($instance->_fieldMeta);
            $instance->fromArray($row, $rowPrefix, true, true);
            $instance->_dirty= array ();
            $instance->_new= false;
        }
        return $instance;
    }

    /**
     * Responsible for loading an instance into a collection.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param array &$objCollection The collection to load the instance into.
     * @param string $className Name of the class.
     * @param mixed $criteria A valid primary key, criteria array, or xPDOCriteria instance.
     * @param array $row The associative array containing the instance data.
     * @param bool $fromCache If the instance is for the cache
     * @param bool|int $cacheFlag Indicates if the objects should be cached and
     * optionally, by specifying an integer value, for how many seconds.
     * @return bool True if a valid instance was loaded, false otherwise.
     */
    public static function _loadCollectionInstance(xPDO & $xpdo, array & $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag=true) {
        $loaded = false;
        if ($obj= xPDOObject :: _loadInstance($xpdo, $className, $criteria, $row)) {
            if (($cacheKey= $obj->getPrimaryKey()) && !$obj->isLazy()) {
                if (is_array($cacheKey)) {
                    $pkval= implode('-', $cacheKey);
                } else {
                    $pkval= $cacheKey;
                }
                /* set OPT_CACHE_DB_COLLECTIONS to 2 to cache instances by primary key from collection result sets */
                if ($xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, array(), 1) == 2 && $xpdo->_cacheEnabled && $cacheFlag) {
                    if (!$fromCache) {
                        $pkCriteria = $xpdo->newQuery($className, $cacheKey, $cacheFlag);
                        $xpdo->toCache($pkCriteria, $obj, $cacheFlag);
                    } else {
                        $obj->_cacheFlag= true;
                    }
                }
                $objCollection[$pkval]= $obj;
                $loaded = true;
            } else {
                $objCollection[]= $obj;
                $loaded = true;
            }
        }
        return $loaded;
    }

    /**
     * Load an instance of an xPDOObject or derivative class.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param mixed $criteria A valid primary key, criteria array, or
     * xPDOCriteria instance.
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return object|null An instance of the requested class, or null if it
     * could not be instantiated.
     */
    public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag= true) {
        $instance= null;
        $fromCache= false;
        if ($className= $xpdo->loadClass($className)) {
            if (!is_object($criteria)) {
                $criteria = $xpdo->getCriteria($className, $criteria, $cacheFlag);
            }
            if (is_object($criteria)) {
                $criteria = $xpdo->addDerivativeCriteria($className, $criteria);
                $row= null;
                if ($xpdo->_cacheEnabled && $criteria->cacheFlag && $cacheFlag) {
                    $row= $xpdo->fromCache($criteria, $className);
                }
                if ($row === null || !is_array($row)) {
                    if ($rows= xPDOObject :: _loadRows($xpdo, $className, $criteria)) {
                        $row= $rows->fetch(PDO::FETCH_ASSOC);
                        $rows->closeCursor();
                    }
                } else {
                    $fromCache= true;
                }
                if (!is_array($row)) {
                    if ($xpdo->getDebug() === true) $xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Fetched empty result set from statement: " . print_r($criteria->sql, true) . " with bindings: " . print_r($criteria->bindings, true));
                } else {
                    $instance= xPDOObject :: _loadInstance($xpdo, $className, $criteria, $row);
                    if (is_object($instance)) {
                        if (!$fromCache && $cacheFlag && $xpdo->_cacheEnabled) {
                            $xpdo->toCache($criteria, $instance, $cacheFlag);
                            if ($xpdo->getOption(xPDO::OPT_CACHE_DB_OBJECTS_BY_PK) && ($cacheKey= $instance->getPrimaryKey()) && !$instance->isLazy()) {
                                $pkCriteria = $xpdo->newQuery($className, $cacheKey, $cacheFlag);
                                $xpdo->toCache($pkCriteria, $instance, $cacheFlag);
                            }
                        }
                        if ($xpdo->getDebug() === true) $xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Loaded object instance: " . print_r($instance->toArray('', true), true));
                    }
                }
            } else {
                $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'No valid statement could be found in or generated from the given criteria.');
            }
        } else {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Invalid class specified: ' . $className);
        }
        return $instance;
    }

    /**
     * Load a collection of xPDOObject instances.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param mixed $criteria A valid primary key, criteria array, or xPDOCriteria instance.
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return array An array of xPDOObject instances or an empty array if no instances are loaded.
     */
    public static function loadCollection(xPDO & $xpdo, $className, $criteria= null, $cacheFlag= true) {
        $objCollection= array ();
        $fromCache = false;
        if (!$className= $xpdo->loadClass($className)) return $objCollection;
        $rows= false;
        $fromCache= false;
        $collectionCaching = (integer) $xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, array(), 1);
        if (!is_object($criteria)) {
            $criteria= $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        if (is_object($criteria)) {
            $criteria = $xpdo->addDerivativeCriteria($className, $criteria);
        }
        if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag) {
            $rows= $xpdo->fromCache($criteria);
            $fromCache = (is_array($rows) && !empty($rows));
        }
        if (!$fromCache && is_object($criteria)) {
            $rows= xPDOObject :: _loadRows($xpdo, $className, $criteria);
        }
        if (is_array ($rows)) {
            foreach ($rows as $row) {
                xPDOObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag);
            }
        } elseif (is_object($rows)) {
            $cacheRows = array();
            while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                xPDOObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag);
                if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag && !$fromCache) $cacheRows[] = $row;
            }
            if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag && !$fromCache) $rows =& $cacheRows;
        }
        if (!$fromCache && $xpdo->_cacheEnabled && $collectionCaching > 0 && $cacheFlag && !empty($rows)) {
            $xpdo->toCache($criteria, $rows, $cacheFlag);
        }
        return $objCollection;
    }

    /**
     * Load a collection of xPDOObject instances and a graph of related objects.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param string|array $graph A related object graph in array or JSON
     * format, e.g. array('relationAlias'=>array('subRelationAlias'=>array()))
     * or {"relationAlias":{"subRelationAlias":{}}}.  Note that the empty arrays
     * are necessary in order for the relation to be recognized.
     * @param mixed $criteria A valid primary key, criteria array, or xPDOCriteria instance.
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return array An array of xPDOObject instances or an empty array if no instances are loaded.
     */
    public static function loadCollectionGraph(xPDO & $xpdo, $className, $graph, $criteria, $cacheFlag) {
        $objCollection = array();
        if ($query= $xpdo->newQuery($className, $criteria, $cacheFlag)) {
            $query = $xpdo->addDerivativeCriteria($className, $query);
            $query->bindGraph($graph);
            $rows = array();
            $fromCache = false;
            $collectionCaching = (integer) $xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, array(), 1);
            if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag) {
                $rows= $xpdo->fromCache($query);
                $fromCache = !empty($rows);
            }
            if (!$fromCache) {
                if ($query->prepare()) {
                    $tstart = microtime(true);
                    if ($query->stmt->execute()) {
                        $xpdo->queryTime += microtime(true) - $tstart;
                        $xpdo->executedQueries++;
                        $objCollection= $query->hydrateGraph($query->stmt, $cacheFlag);
                    } else {
                        $xpdo->queryTime += microtime(true) - $tstart;
                        $xpdo->executedQueries++;
                        $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error {$query->stmt->errorCode()} executing query: {$query->sql} - " . print_r($query->stmt->errorInfo(), true));
                    }
                } else {
                    $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error {$xpdo->errorCode()} preparing statement: {$query->sql} - " . print_r($xpdo->errorInfo(), true));
                }
            } elseif (!empty($rows)) {
                $objCollection= $query->hydrateGraph($rows, $cacheFlag);
            }
        }
        return $objCollection;
    }

    /**
     * Get a set of column names from an xPDOObject for use in SQL queries.
     *
     * @static
     * @param xPDO &$xpdo A reference to an initialized xPDO instance.
     * @param string $className The class name to get columns from.
     * @param string $tableAlias An optional alias for the table in the query.
     * @param string $columnPrefix An optional prefix to prepend to each column name.
     * @param array $columns An optional array of field names to include or exclude
     * (include is default behavior).
     * @param boolean $exclude Determines if any specified columns should be included
     * or excluded from the set of results.
     * @return string A comma-delimited list of the field names for use in a SELECT clause.
     */
    public static function getSelectColumns(xPDO & $xpdo, $className, $tableAlias= '', $columnPrefix= '', $columns= array (), $exclude= false) {
        $columnarray= array ();
        $aColumns= $xpdo->getFields($className);
        if ($aColumns) {
            if (!empty ($tableAlias)) {
                $tableAlias= $xpdo->escape($tableAlias);
                $tableAlias.= '.';
            }
            if (!$exclude && !empty($columns)) {
                foreach ($columns as $column) {
                    if (!in_array($column, array_keys($aColumns))) {
                        continue;
                    }
                    $columnarray[$column]= "{$tableAlias}" . $xpdo->escape($column);
                    if (!empty ($columnPrefix)) {
                        $columnarray[$column]= $columnarray[$column] . " AS " . $xpdo->escape("{$columnPrefix}{$column}");
                    }
                }
            } else {
                foreach (array_keys($aColumns) as $k) {
                    if ($exclude && in_array($k, $columns)) {
                        continue;
                    }
                    elseif (empty ($columns)) {
                        $columnarray[$k]= "{$tableAlias}" . $xpdo->escape($k);
                    }
                    elseif ($exclude || in_array($k, $columns)) {
                        $columnarray[$k]= "{$tableAlias}" . $xpdo->escape($k);
                    } else {
                        continue;
                    }
                    if (!empty ($columnPrefix)) {
                        $columnarray[$k]= $columnarray[$k] . " AS " . $xpdo->escape("{$columnPrefix}{$k}");
                    }
                }
            }
        }
        return implode(', ', $columnarray);
    }

    /**
     * Constructor
     *
     * Do not call the constructor directly; see {@link xPDO::newObject()}.
     *
     * All derivatives of xPDOObject must redeclare this method, and must call
     * the parent method explicitly before any additional logic is executed, e.g.
     *
     * <code>
     * public function __construct(xPDO & $xpdo) {
     *     parent  :: __construct($xpdo);
     *     // Any additional constructor tasks here
     * }
     * </code>
     *
     * @access public
     * @param xPDO &$xpdo A reference to a valid xPDO instance.
     * @return xPDOObject
     */
    public function __construct(xPDO & $xpdo) {
        $this->xpdo= & $xpdo;
        $this->container= $xpdo->config['dbname'];
        $this->_class= get_class($this);
        $pos= strrpos($this->_class, '_');
        if ($pos !== false && substr($this->_class, $pos + 1) == $xpdo->config['dbtype']) {
            $this->_class= substr($this->_class, 0, $pos);
        }
        $this->_package= $xpdo->getPackage($this->_class);
        $this->_alias= $this->_class;
        $this->_table= $xpdo->getTableName($this->_class);
        $this->_tableMeta= $xpdo->getTableMeta($this->_class);
        $this->_fields= $xpdo->getFields($this->_class);
        $this->_fieldMeta= $xpdo->getFieldMeta($this->_class);
        $this->_fieldAliases= $xpdo->getFieldAliases($this->_class);
        $this->_aggregates= $xpdo->getAggregates($this->_class);
        $this->_composites= $xpdo->getComposites($this->_class);
        if ($relatedObjs= array_merge($this->_aggregates, $this->_composites)) {
            foreach ($relatedObjs as $aAlias => $aMeta) {
                if (!array_key_exists($aAlias, $this->_relatedObjects)) {
                    if ($aMeta['cardinality'] == 'many') {
                        $this->_relatedObjects[$aAlias]= array ();
                    }
                    else {
                        $this->_relatedObjects[$aAlias]= null;
                    }
                }
            }
        }
        foreach ($this->_fieldAliases as $fieldAlias => $field) {
            $this->addFieldAlias($field, $fieldAlias);
        }
        $this->setDirty();
    }

    /**
     * Add an alias as a reference to an actual field of the object.
     *
     * @param string $field The field name to create a reference to.
     * @param string $alias The name of the reference.
     * @return bool True if the reference is added successfully.
     */
    public function addFieldAlias($field, $alias) {
        $added = false;
        if (array_key_exists($field, $this->_fields)) {
            if (!array_key_exists($alias, $this->_fields)) {
                $this->_fields[$alias] =& $this->_fields[$field];
                if (!array_key_exists($alias, $this->_fieldAliases)) {
                    $this->_fieldAliases[$alias] = $field;
                    if (!array_key_exists($alias, $this->xpdo->map[$this->_class]['fieldAliases'])) {
                        $this->xpdo->map[$this->_class]['fieldAliases'][$alias]= $field;
                    }
                }
                $added = true;
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "The alias {$alias} is already in use as a field name in objects of class {$this->_class}", '', __METHOD__, __FILE__, __LINE__);
            }
        }
        return $added;
    }

    /**
     * Get an option value for this instance.
     *
     * @param string $key The option key to retrieve a value for.
     * @param array|null $options An optional array to search for a value in first.
     * @param mixed $default A default value to return if no value is found; null is the default.
     * @return mixed The value of the option or the provided default if it is not set.
     */
    public function getOption($key, $options = null, $default = null) {
        if (is_array($options) && array_key_exists($key, $options)) {
            $value= $options[$key];
        } elseif (array_key_exists($key, $this->_options)) {
            $value= $this->_options[$key];
        } else {
            $value= $this->xpdo->getOption($key, null, $default);
        }
        return $value;
    }

    /**
     * Set an option value for this instance.
     *
     * @param string $key The option key to set a value for.
     * @param mixed $value A value to assign to the option.
     */
    public function setOption($key, $value) {
        $this->_options[$key]= $value;
    }

    public function __get($name) {
        if ($this->getOption(xPDO::OPT_HYDRATE_FIELDS) && array_key_exists($name, $this->_fields)) {
            return $this->_fields[$name];
        } elseif ($this->getOption(xPDO::OPT_HYDRATE_RELATED_OBJECTS)) {
            if (array_key_exists($name, $this->_composites)) {
                $fkMeta = $this->_composites[$name];
            } elseif (array_key_exists($name, $this->_aggregates)) {
                $fkMeta = $this->_aggregates[$name];
            } else {
                return null;
            }
        } else {
            return null;
        }
        if ($fkMeta['cardinality'] === 'many') {
            return $this->getMany($name);
        } else {
            return $this->getOne($name);
        }
    }

    public function __set($name, $value) {
        if ($this->getOption(xPDO::OPT_HYDRATE_FIELDS) && array_key_exists($name, $this->_fields)) {
            return $this->_setRaw($name, $value);
        } elseif ($this->getOption(xPDO::OPT_HYDRATE_RELATED_OBJECTS)) {
            if (array_key_exists($name, $this->_composites)) {
                $fkMeta = $this->_composites[$name];
            } elseif (array_key_exists($name, $this->_aggregates)) {
                $fkMeta = $this->_aggregates[$name];
            } else {
                return false;
            }
        } else {
            return false;
        }
        if ($fkMeta['cardinality'] === 'many') {
            return $this->addMany($value, $name);
        } else {
            return $this->addOne($value, $name);
        }
    }

    public function __isset($name) {
        return ($this->getOption(xPDO::OPT_HYDRATE_FIELDS) && array_key_exists($name, $this->_fields) && isset($this->_fields[$name]))
            || ($this->getOption(xPDO::OPT_HYDRATE_RELATED_OBJECTS)
                && ((array_key_exists($name, $this->_composites) && isset($this->_composites[$name]))
                || (array_key_exists($name, $this->_aggregates) && isset($this->_aggregates[$name]))));
    }

    /**
     * Set a field value by the field key or name.
     *
     * @todo Define and implement field validation.
     *
     * @param string $k The field key or name.
     * @param mixed $v The value to set the field to.
     * @param string|callable $vType A string indicating the format of the
     * provided value parameter, or a callable function that should be used to
     * set the field value, overriding the default behavior.
     * @return boolean Determines whether the value was set successfully and was
     * determined to be dirty (i.e. different from the previous value).
     */
    public function set($k, $v= null, $vType= '') {
        $set= false;
        $callback= '';
        $callable= !empty($vType) && is_callable($vType, false, $callback) ? true : false;
        if (!$callable && isset($this->_fieldMeta[$k]['callback'])) {
            $callable = is_callable($this->_fieldMeta[$k]['callback'], false, $callback);
        }
        $oldValue= null;
        $k = $this->getField($k);
        if (is_string($k) && !empty($k)) {
            if (array_key_exists($k, $this->_fieldMeta)) {
                $oldValue= $this->_fields[$k];
                if (isset($this->_fieldMeta[$k]['generated']) && !$this->_fieldMeta[$k]['generated'] === 'callback') {
                    return false;
                }
                if ($callable && $callback) {
                    $set = call_user_func_array($callback, array($k, $v, $this));
                } else {
                    if (is_string($v) && $this->getOption(xPDO::OPT_ON_SET_STRIPSLASHES)) {
                        $v= stripslashes($v);
                    }
                    if ($oldValue !== $v) {
                        //type validation
                        $phptype= $this->_fieldMeta[$k]['phptype'];
                        $dbtype= $this->_fieldMeta[$k]['dbtype'];
                        $allowNull= isset($this->_fieldMeta[$k]['null']) ? (boolean) $this->_fieldMeta[$k]['null'] : true;
                        if ($v === null) {
                            if ($allowNull) {
                                $this->_fields[$k]= null;
                                $set= true;
                            } else {
                                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "{$this->_class}: Attempt to set NOT NULL field {$k} to NULL");
                            }
                        }
                        else {
                            switch ($phptype) {
                                case 'timestamp' :
                                case 'datetime' :
                                    $ts= false;
                                    if (preg_match('/int/i', $dbtype)) {
                                        if (strtolower($vType) == 'integer' || is_int($v) || $v == '0') {
                                            $ts= (integer) $v;
                                        } else {
                                            $ts= strtotime($v);
                                        }
                                        if ($ts === false) {
                                            $ts= 0;
                                        }
                                        $this->_fields[$k]= $ts;
                                        $set= true;
                                    } else {
                                        if ($vType == 'utc' || in_array($v, $this->xpdo->driver->_currentTimestamps) || $v === '0000-00-00 00:00:00') {
                                            $this->_fields[$k]= (string) $v;
                                            $set= true;
                                        } else {
                                            if (strtolower($vType) == 'integer' || is_int($v)) {
                                                $ts= intval($v);
                                            } elseif (is_string($v) && !empty($v)) {
                                                $ts= strtotime($v);
                                            }
                                            if ($ts !== false) {
                                                $this->_fields[$k]= date('Y-m-d H:i:s', $ts);
                                                $set= true;
                                            }
                                        }
                                    }
                                    break;
                                case 'date' :
                                    if (preg_match('/int/i', $dbtype)) {
                                        if (strtolower($vType) == 'integer' || is_int($v) || $v == '0') {
                                            $ts= (integer) $v;
                                        } else {
                                            $ts= strtotime($v);
                                        }
                                        if ($ts === false) {
                                            $ts= 0;
                                        }
                                        $this->_fields[$k]= $ts;
                                        $set= true;
                                    } else {
                                        if ($vType == 'utc' || in_array($v, $this->xpdo->driver->_currentDates) || $v === '0000-00-00') {
                                            $this->_fields[$k]= $v;
                                            $set= true;
                                        } else {
                                            if (strtolower($vType) == 'integer' || is_int($v)) {
                                                $ts= intval($v);
                                            } elseif (is_string($v) && !empty($v)) {
                                                $ts= strtotime($v);
                                            }
                                            $ts= strtotime($v);
                                            if ($ts !== false) {
                                                $this->_fields[$k]= date('Y-m-d H:i:s', $ts);
                                                $set= true;
                                            }
                                        }
                                    }
                                    break;
                                case 'boolean' :
                                    $this->_fields[$k]= intval($v);
                                    $set= true;
                                    break;
                                case 'integer' :
                                    $this->_fields[$k]= intval($v);
                                    $set= true;
                                    break;
                                case 'array' :
                                    if (is_object($v) && $v instanceof xPDOObject) {
                                        $v = $v->toArray();
                                    }
                                    if (is_array($v)) {
                                        $this->_fields[$k]= serialize($v);
                                        $set= true;
                                    }
                                    break;
                                case 'json' :
                                    if (is_object($v) && $v instanceof xPDOObject) {
                                        $v = $v->toArray();
                                    }
                                    if (is_string($v)) {
                                        $v= $this->xpdo->fromJSON($v, true);
                                    }
                                    if (is_array($v)) {
                                        $this->_fields[$k]= $this->xpdo->toJSON($v);
                                        $set= true;
                                    }
                                    break;
                                default :
                                    $this->_fields[$k]= $v;
                                    $set= true;
                            }
                        }
                    }
                }
            } elseif ($this->getOption(xPDO::OPT_HYDRATE_ADHOC_FIELDS)) {
                $oldValue= isset($this->_fields[$k]) ? $this->_fields[$k] : null;
                if ($callable) {
                    $set = call_user_func_array($callback, array($k, $v, $this));
                } else {
                    $this->_fields[$k]= $v;
                    $set= true;
                }
            }
            if ($set && $oldValue !== $this->_fields[$k]) {
                $this->setDirty($k);
            } else {
                $set= false;
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'xPDOObject - Called set() with an invalid field name: ' . print_r($k, 1));
        }
        return $set;
    }

    /**
     * Get a field value (or a set of values) by the field key(s) or name(s).
     *
     * Warning: do not use the $format parameter if retrieving multiple values of
     * different types, as the format string will be applied to all types, most
     * likely with unpredictable results.  Optionally, you can supply an associate
     * array of format strings with the field key as the key for the format array.
     *
     * @param string|array $k A string (or an array of strings) representing the field
     * key or name.
     * @param string|array $format An optional variable (or an array of variables) to
     * format the return value(s).
     * @param mixed $formatTemplate An additional optional variable that can be used in
     * formatting the return value(s).
     * @return mixed The value(s) of the field(s) requested.
     */
    public function get($k, $format = null, $formatTemplate= null) {
        $value= null;
        if (is_array($k)) {
            $lazy = array_intersect($k, $this->_lazy);
            if ($lazy) {
                $this->_loadFieldData($lazy);
            }
            foreach ($k as $key) {
                if (array_key_exists($key, $this->_fields)) {
                    if (is_array($format) && isset ($format[$key])) {
                        $formatTpl= null;
                        if (is_array ($formatTemplate) && isset ($formatTemplate[$key])) {
                            $formatTpl= $formatTemplate[$key];
                        }
                        $value[$key]= $this->get($key, $format[$key], $formatTpl);
                    } elseif (!empty ($format) && is_string($format)) {
                        $value[$key]= $this->get($key, $format, $formatTemplate);
                    } else {
                        $value[$key]= $this->get($key);
                    }
                }
            }
        } elseif (is_string($k) && !empty($k)) {
            if (array_key_exists($k, $this->_fields)) {
                if ($this->isLazy($k)) {
                    $this->_loadFieldData($k);
                }
                $dbType= $this->_getDataType($k);
                $fieldType= $this->_getPHPType($k);
                $value= $this->_fields[$k];
                if ($value !== null) {
                    switch ($fieldType) {
                        case 'boolean' :
                            $value= (boolean) $value;
                            break;
                        case 'integer' :
                            $value= intval($value);
                            if (is_string($format) && !empty ($format)) {
                                if (strpos($format, 're:') === 0) {
                                    if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                        $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                    }
                                } else {
                                    $value= sprintf($format, $value);
                                }
                            }
                            break;
                        case 'float' :
                            $value= (float) $value;
                            if (is_string($format) && !empty ($format)) {
                                if (strpos($format, 're:') === 0) {
                                    if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                        $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                    }
                                } else {
                                    $value= sprintf($format, $value);
                                }
                            }
                            break;
                        case 'timestamp' :
                        case 'datetime' :
                            if (preg_match('/int/i', $dbType)) {
                                $ts= intval($value);
                            } elseif (in_array($value, $this->xpdo->driver->_currentTimestamps)) {
                                $ts= time();
                            } else {
                                $ts= strtotime($value);
                            }
                            if ($ts !== false && !empty($value)) {
                                if (is_string($format) && !empty ($format)) {
                                    if (strpos($format, 're:') === 0) {
                                        $value= date('Y-m-d H:i:s', $ts);
                                        if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                            $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                        }
                                    } elseif (strpos($format, '%') === false) {
                                        $value= date($format, $ts);
                                    } else {
                                        $value= strftime($format, $ts);
                                    }
                                } else {
                                    $value= date('Y-m-d H:i:s', $ts);
                                }
                            }
                            break;
                        case 'date' :
                            if (preg_match('/int/i', $dbType)) {
                                $ts= intval($value);
                            } elseif (in_array($value, $this->xpdo->driver->_currentDates)) {
                                $ts= time();
                            } else {
                                $ts= strtotime($value);
                            }
                            if ($ts !== false && !empty($value)) {
                                if (is_string($format) && !empty ($format)) {
                                    if (strpos($format, 're:') === 0) {
                                        $value= date('Y-m-d', $ts);
                                        if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                            $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                        }
                                    } elseif (strpos($format, '%') === false) {
                                        $value= date($format, $ts);
                                    } elseif ($ts !== false) {
                                        $value= strftime($format, $ts);
                                    }
                                } else {
                                    $value= date('Y-m-d', $ts);
                                }
                            }
                            break;
                        case 'array' :
                            if (is_string($value)) {
                                $value= unserialize($value);
                            }
                            break;
                        case 'json' :
                            if (is_string($value) && strlen($value) > 1) {
                                $value= $this->xpdo->fromJSON($value, true);
                            }
                            break;
                        default :
                            if (is_string($format) && !empty ($format)) {
                                if (strpos($format, 're:') === 0) {
                                    if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                        $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                    }
                                } else {
                                    $value= sprintf($format, $value);
                                }
                            }
                            break;
                    }
                }
            }
        }
        return $value;
    }

    /**
     * Gets an object related to this instance by a foreign key relationship.
     *
     * Use this for 1:? (one:zero-or-one) or 1:1 relationships, which you can
     * distinguish by setting the nullability of the field representing the
     * foreign key.
     *
     * For all 1:* relationships for this instance, see {@link getMany()}.
     *
     * @see xPDOObject::getMany()
     * @see xPDOObject::addOne()
     * @see xPDOObject::addMany()
     *
     * @param string $alias Alias of the foreign class representing the related
     * object.
     * @param object $criteria xPDOCriteria object to get the related objects
     * @param boolean|integer $cacheFlag Indicates if the object should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return xPDOObject|null The related object or null if no instance exists.
     */
    public function & getOne($alias, $criteria= null, $cacheFlag= true) {
        $object= null;
        if ($fkdef= $this->getFKDefinition($alias)) {
            $k= $fkdef['local'];
            $fk= $fkdef['foreign'];
            if (isset ($this->_relatedObjects[$alias])) {
                if (is_object($this->_relatedObjects[$alias])) {
                    $object= & $this->_relatedObjects[$alias];
                    return $object;
                }
            }
            if ($criteria === null) {
                $criteria= array ($fk => $this->get($k));
                if (isset($fkdef['criteria']) && isset($fkdef['criteria']['foreign'])) {
                    $criteria= array($fkdef['criteria']['foreign'], $criteria);
                }
            }
            if ($object= $this->xpdo->getObject($fkdef['class'], $criteria, $cacheFlag)) {
                $this->_relatedObjects[$alias]= $object;
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "Could not getOne: foreign key definition for alias {$alias} not found.");
        }
        return $object;
    }

    /**
     * Gets a collection of objects related by aggregate or composite relations.
     *
     * @see xPDOObject::getOne()
     * @see xPDOObject::addOne()
     * @see xPDOObject::addMany()
     *
     * @param string $alias Alias of the foreign class representing the related
     * object.
     * @param object $criteria xPDOCriteria object to get the related objects
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return array A collection of related objects or an empty array.
     */
    public function & getMany($alias, $criteria= null, $cacheFlag= true) {
        $collection= $this->_getRelatedObjectsByFK($alias, $criteria, $cacheFlag);
        return $collection;
    }

    /**
     * Get an xPDOIterator for a collection of objects related by aggregate or composite relations.
     *
     * @param string $alias The alias of the relation.
     * @param null|array|xPDOCriteria $criteria A valid xPDO criteria expression.
     * @param bool|int $cacheFlag Indicates if the objects should be cached and optionally, by
     * specifying  an integer values, for how many seconds.
     * @return bool|xPDOIterator An iterator for the collection or false if no relation is found.
     */
    public function getIterator($alias, $criteria= null, $cacheFlag= true) {
        $iterator = false;
        $fkMeta= $this->getFKDefinition($alias);
        if ($fkMeta) {
            $fkCriteria = isset($fkMeta['criteria']) && isset($fkMeta['criteria']['foreign']) ? $fkMeta['criteria']['foreign'] : null;
            if ($criteria === null) {
                $criteria= array($fkMeta['foreign'] => $this->get($fkMeta['local']));
                if ($fkCriteria !== null) {
                    $criteria = array($fkCriteria, $criteria);
                }
            } else {
                $criteria= $this->xpdo->newQuery($fkMeta['class'], $criteria);
                $addCriteria = array("{$criteria->getAlias()}.{$fkMeta['foreign']}" => $this->get($fkMeta['local']));
                if ($fkCriteria !== null) {
                    $fkAddCriteria = array();
                    foreach ($fkCriteria as $fkCritKey => $fkCritVal) {
                        if (is_numeric($fkCritKey)) continue;
                        $fkAddCriteria["{$criteria->getAlias()}.{$fkCritKey}"] = $fkCritVal;
                    }
                    if (!empty($fkAddCriteria)) {
                        $addCriteria = array($fkAddCriteria, $addCriteria);
                    }
                }
                $criteria->andCondition($addCriteria);
            }
            $iterator = $this->xpdo->getIterator($fkMeta['class'], $criteria, $cacheFlag);
        }
        return $iterator;
    }

    /**
     * Adds an object related to this instance by a foreign key relationship.
     *
     * @see xPDOObject::getOne()
     * @see xPDOObject::getMany()
     * @see xPDOObject::addMany()
     *
     * @param mixed &$obj A single object to be related to this instance.
     * @param string $alias The relation alias of the related object (only
     * required if more than one relation exists to the same foreign class).
     * @return boolean True if the related object was added to this object.
     */
    public function addOne(& $obj, $alias= '') {
        $added= false;
        if (is_object($obj)) {
            if (empty ($alias)) {
                if ($obj->_alias == $obj->_class) {
                    $aliases = $this->_getAliases($obj->_class, 1);
                    if (!empty($aliases)) {
                        $obj->_alias = reset($aliases);
                    }
                }
                $alias= $obj->_alias;
            }
            $fkMeta= $this->getFKDefinition($alias);
            if ($fkMeta && $fkMeta['cardinality'] === 'one') {
                $obj->_alias= $alias;
                $fk= $fkMeta['foreign'];
                $key= $fkMeta['local'];
                $owner= isset ($fkMeta['owner']) ? $fkMeta['owner'] : 'local';
                $kval= $this->get($key);
                $fkval= $obj->get($fk);
                if ($owner == 'local') {
                    $fkCriteria = isset($fkMeta['criteria']) && isset($fkMeta['criteria']['foreign']) ? $fkMeta['criteria']['foreign'] : null;
                    $obj->set($fk, $kval);
                    if (is_array($fkCriteria)) {
                        foreach ($fkCriteria as $fkCritKey => $fkCritVal) {
                            if (is_numeric($fkCritKey)) continue;
                            $obj->set($fkCritKey, $fkCritVal);
                        }
                    }
                }
                else {
                    $this->set($key, $fkval);
                    $fkCriteria = isset($fkMeta['criteria']) && isset($fkMeta['criteria']['local']) ? $fkMeta['criteria']['local'] : null;
                    if (is_array($fkCriteria)) {
                        foreach ($fkCriteria as $fkCritKey => $fkCritVal) {
                            if (is_numeric($fkCritKey)) continue;
                            $this->set($fkCritKey, $fkCritVal);
                        }
                    }
                }

                $this->_relatedObjects[$obj->_alias]= $obj;
                $this->setDirty($key);
                $added= true;
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "Foreign key definition for class {$obj->class}, alias {$obj->_alias} not found, or cardinality is not 'one'.");
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "Attempt to add a non-object to a relation with alias ({$alias})");
        }
        if (!$added) {
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "Could not add related object! " . (is_object($obj) ? print_r($obj->toArray(), true) : ''));
        }
        return $added;
    }

    /**
     * Adds an object or collection of objects related to this class.
     *
     * This method adds an object or collection of objects in a one-to-
     * many foreign key relationship with this object to the internal list of
     * related objects.  By adding these related objects, you can cascade
     * {@link xPDOObject::save()}, {@link xPDOObject::remove()}, and other
     * operations based on the type of relationships defined.
     *
     * @see xPDOObject::addOne()
     * @see xPDOObject::getOne()
     * @see xPDOObject::getMany()
     *
     * @param mixed &$obj A single object or collection of objects to be related
     * to this instance via the intersection class.
     * @param string $alias An optional alias, required only for instances where
     * you have more than one relation defined to the same class.
     * @return boolean Indicates if the addMany was successful.
     */
    public function addMany(& $obj, $alias= '') {
        $added= false;
        if (!is_array($obj)) {
            if (is_object($obj)) {
                if (empty ($alias)) {
                    if ($obj->_alias == $obj->_class) {
                        $aliases = $this->_getAliases($obj->_class, 1);
                        if (!empty($aliases)) {
                            $obj->_alias = reset($aliases);
                        }
                    }
                    $alias= $obj->_alias;
                }
                if ($fkMeta= $this->getFKDefinition($alias)) {
                    $obj->_alias= $alias;
                    if ($fkMeta['cardinality'] === 'many') {
                        if ($obj->_new) {
                            $objpk= '__new' . (isset ($this->_relatedObjects[$alias]) ? count($this->_relatedObjects[$alias]) : 0);
                        } else {
                            $objpk= $obj->getPrimaryKey();
                            if (is_array($objpk)) {
                                $objpk= implode('-', $objpk);
                            }
                        }
                        $this->_relatedObjects[$alias][$objpk]= $obj;
                        if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Added related object with alias: ' . $alias . ' and pk: ' . $objpk . "\n" . print_r($obj->toArray('', true), true));
                        $added= true;
                    }
                }
            }
        } else {
            foreach ($obj as $o) {
                $added= $this->addMany($o, $alias);
            }
        }
        return $added;
    }

    /**
     * Persist new or changed objects to the database container.
     *
     * Inserts or updates the database record representing this object and any
     * new or changed related object records.  Both aggregate and composite
     * related objects will be saved as appropriate, before or following the
     * save operation on the controlling instance.
     *
     * @param boolean|integer $cacheFlag Indicates if the saved object(s) should
     * be cached and optionally, by specifying an integer value, for how many
     * seconds before expiring.  Overrides the cacheFlag for the object(s).
     * @return boolean Returns true on success, false on failure.
     */
    public function save($cacheFlag= null) {
        if ($this->isLazy()) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Attempt to save lazy object: ' . print_r($this->toArray('', true), 1));
            return false;
        }
        $result= true;
        $sql= '';
        $pk= $this->getPrimaryKey();
        $pkn= $this->getPK();
        $pkGenerated= false;
        if ($this->isNew()) {
            $this->setDirty();
        }
        if ($this->getOption(xPDO::OPT_VALIDATE_ON_SAVE)) {
            if (!$this->validate()) {
                return false;
            }
        }
        if (!$this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not get connection for writing data", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        $this->_saveRelatedObjects();
        if (!empty ($this->_dirty)) {
            $cols= array ();
            $bindings= array ();
            $updateSql= array ();
            foreach (array_keys($this->_dirty) as $_k) {
                if (!array_key_exists($_k, $this->_fieldMeta)) {
                    continue;
                }
                if (isset($this->_fieldMeta[$_k]['generated'])) {
                    if (!$this->_new || !isset($this->_fields[$_k]) || empty($this->_fields[$_k])) {
                        $pkGenerated= true;
                        continue;
                    }
                }
                if ($this->_fieldMeta[$_k]['phptype'] === 'password') {
                    $this->_fields[$_k]= $this->encode($this->_fields[$_k], 'password');
                }
                $fieldType= PDO::PARAM_STR;
                $fieldValue= $this->_fields[$_k];
                if (in_array($this->_fieldMeta[$_k]['phptype'], array ('datetime', 'timestamp')) && !empty($this->_fieldMeta[$_k]['attributes']) && $this->_fieldMeta[$_k]['attributes'] == 'ON UPDATE CURRENT_TIMESTAMP') {
                    $this->_fields[$_k]= date('Y-m-d H:i:s');
                    continue;
                }
                elseif ($fieldValue === null || $fieldValue === 'NULL') {
                    if ($this->_new) continue;
                    $fieldType= PDO::PARAM_NULL;
                    $fieldValue= null;
                }
                elseif (in_array($this->_fieldMeta[$_k]['phptype'], array ('timestamp', 'datetime')) && in_array($fieldValue, $this->xpdo->driver->_currentTimestamps, true)) {
                    $this->_fields[$_k]= date('Y-m-d H:i:s');
                    continue;
                }
                elseif (in_array($this->_fieldMeta[$_k]['phptype'], array ('date')) && in_array($fieldValue, $this->xpdo->driver->_currentDates, true)) {
                    $this->_fields[$_k]= date('Y-m-d');
                    continue;
                }
                elseif ($this->_fieldMeta[$_k]['phptype'] == 'timestamp' && preg_match('/int/i', $this->_fieldMeta[$_k]['dbtype'])) {
                    $fieldType= PDO::PARAM_INT;
                }
                elseif (!in_array($this->_fieldMeta[$_k]['phptype'], array ('string','password','datetime','timestamp','date','time','array','json','float'))) {
                    $fieldType= PDO::PARAM_INT;
                }
                if ($this->_new) {
                    $cols[$_k]= $this->xpdo->escape($_k);
                    $bindings[":{$_k}"]['value']= $fieldValue;
                    $bindings[":{$_k}"]['type']= $fieldType;
                } else {
                    $bindings[":{$_k}"]['value']= $fieldValue;
                    $bindings[":{$_k}"]['type']= $fieldType;
                    $updateSql[]= $this->xpdo->escape($_k) . " = :{$_k}";
                }
            }
            if ($this->_new) {
                $sql= "INSERT INTO {$this->_table} (" . implode(', ', array_values($cols)) . ") VALUES (" . implode(', ', array_keys($bindings)) . ")";
            } else {
                if ($pk && $pkn) {
                    if (is_array($pkn)) {
                        $iteration= 0;
                        $where= '';
                        foreach ($pkn as $k => $v) {
                            $vt= PDO::PARAM_INT;
                            if (in_array($this->_fieldMeta[$k]['phptype'], array('string', 'float'))) {
                                $vt= PDO::PARAM_STR;
                            }
                            if ($iteration) {
                                $where .= " AND ";
                            }
                            $where .= $this->xpdo->escape($k) . " = :{$k}";
                            $bindings[":{$k}"]['value']= $this->_fields[$k];
                            $bindings[":{$k}"]['type']= $vt;
                            $iteration++;
                        }
                    } else {
                        $pkn= $this->getPK();
                        $pkt= PDO::PARAM_INT;
                        if (in_array($this->_fieldMeta[$pkn]['phptype'], array('string', 'float'))) {
                            $pkt= PDO::PARAM_STR;
                        }
                        $bindings[":{$pkn}"]['value']= $pk;
                        $bindings[":{$pkn}"]['type']= $pkt;
                        $where= $this->xpdo->escape($pkn) . ' = :' . $pkn;
                    }
                    if (!empty ($updateSql)) {
                        $sql= "UPDATE {$this->_table} SET " . implode(',', $updateSql) . " WHERE {$where}";
                    }
                }
            }
            if (!empty ($sql) && $criteria= new xPDOCriteria($this->xpdo, $sql)) {
                if ($criteria->prepare()) {
                    if (!empty ($bindings)) {
                        $criteria->bind($bindings, true, false);
                    }
                    if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Executing SQL:\n{$sql}\nwith bindings:\n" . print_r($bindings, true));
                    $tstart = microtime(true);
                    if (!$result= $criteria->stmt->execute()) {
                        $this->xpdo->queryTime += microtime(true) - $tstart;
                        $this->xpdo->executedQueries++;
                        $errorInfo= $criteria->stmt->errorInfo();
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error " . $criteria->stmt->errorCode() . " executing statement:\n" . $criteria->toSQL() . "\n" . print_r($errorInfo, true));
                        if (($errorInfo[1] == '1146' || $errorInfo[1] == '1') && $this->getOption(xPDO::OPT_AUTO_CREATE_TABLES)) {
                            if ($this->xpdo->getManager() && $this->xpdo->manager->createObjectContainer($this->_class) === true) {
                                $tstart = microtime(true);
                                if (!$result= $criteria->stmt->execute()) {
                                    $this->xpdo->queryTime += microtime(true) - $tstart;
                                    $this->xpdo->executedQueries++;
                                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error " . $criteria->stmt->errorCode() . " executing statement:\n{$sql}\n");
                                } else {
                                    $this->xpdo->queryTime += microtime(true) - $tstart;
                                    $this->xpdo->executedQueries++;
                                }
                            } else {
                                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error " . $this->xpdo->errorCode() . " attempting to create object container for class {$this->_class}:\n" . print_r($this->xpdo->errorInfo(), true));
                            }
                        }
                    } else {
                        $this->xpdo->queryTime += microtime(true) - $tstart;
                        $this->xpdo->executedQueries++;
                    }
                } else {
                    $result= false;
                }
                if ($result) {
                    if ($pkn && !$pk) {
                        if ($pkGenerated) {
                            $this->_fields[$this->getPK()]= $this->xpdo->lastInsertId();
                        }
                        $pk= $this->getPrimaryKey();
                    }
                    if ($pk || !$this->getPK()) {
                        $this->_dirty= array();
                        $this->_validated= array();
                        $this->_new= false;
                    }
                    $callback = $this->getOption(xPDO::OPT_CALLBACK_ON_SAVE);
                    if ($callback && is_callable($callback)) {
                        call_user_func($callback, array('className' => $this->_class, 'criteria' => $criteria, 'object' => $this));
                    }
                    if ($this->xpdo->_cacheEnabled && $pk && ($cacheFlag || ($cacheFlag === null && $this->_cacheFlag))) {
                        $cacheKey= $this->xpdo->newQuery($this->_class, $pk, $cacheFlag);
                        if (is_bool($cacheFlag)) {
                            $expires= 0;
                        } else {
                            $expires= intval($cacheFlag);
                        }
                        $this->xpdo->toCache($cacheKey, $this, $expires, array('modified' => true));
                    }
                }
            }
        }
        $this->_saveRelatedObjects();
        if ($result) {
            $this->_dirty= array ();
            $this->_validated= array ();
        }
        return $result;
    }

    /**
     * Searches for any related objects with pending changes to save.
     *
     * @access protected
     * @uses xPDOObject::_saveRelatedObject()
     * @return integer The number of related objects processed.
     */
    protected function _saveRelatedObjects() {
        $saved= 0;
        if (!empty ($this->_relatedObjects)) {
            foreach ($this->_relatedObjects as $alias => $ro) {
                $objects= array ();
                if (is_object($ro)) {
                    $primaryKey= $ro->_new ? '__new' : $ro->getPrimaryKey();
                    if (is_array($primaryKey)) $primaryKey= implode('-', $primaryKey);
                    $objects[$primaryKey]= & $ro;
                    $cardinality= 'one';
                }
                elseif (is_array($ro)) {
                    $objects= $ro;
                    $cardinality= 'many';
                }
                if (!empty($objects)) {
                    foreach ($objects as $pk => $obj) {
                        if ($fkMeta= $this->getFKDefinition($alias)) {
                            if ($this->_saveRelatedObject($obj, $fkMeta)) {
                                if ($cardinality == 'many') {
                                    $newPk= $obj->getPrimaryKey();
                                    if (is_array($newPk)) $newPk= implode('-', $newPk);
                                    if ($pk != $newPk) {
                                        $this->_relatedObjects[$alias][$newPk]= $obj;
                                        unset($this->_relatedObjects[$alias][$pk]);
                                    }
                                }
                                $saved++;
                            }
                        }
                    }
                }
            }
        }
        return $saved;
    }

    /**
     * Save a related object with pending changes.
     *
     * This function is also responsible for setting foreign keys when new
     * related objects are being saved, as well as local keys when the host
     * object is new and needs the foreign key.
     *
     * @access protected
     * @param xPDOObject &$obj A reference to the related object.
     * @param array $fkMeta The meta data representing the relation.
     * @return boolean True if a related object was dirty and saved successfully.
     */
    protected function _saveRelatedObject(& $obj, $fkMeta) {
        $saved= false;
        $local= $fkMeta['local'];
        $foreign= $fkMeta['foreign'];
        $cardinality= $fkMeta['cardinality'];
        $owner= isset ($fkMeta['owner']) ? $fkMeta['owner'] : '';
        if (!$owner) {
            $owner= $cardinality === 'many' ? 'foreign' : 'local';
        }
        $criteria = isset($fkMeta['criteria']) ? $fkMeta['criteria'] : null;
        if ($owner === 'local' && $fk= $this->get($local)) {
            $obj->set($foreign, $fk);
            if (isset($criteria['foreign']) && is_array($criteria['foreign'])) {
                foreach ($criteria['foreign'] as $critKey => $critVal) {
                    if (is_numeric($critKey)) continue;
                    $obj->set($critKey, $critVal);
                }
            }
            $saved= $obj->save();
        } elseif ($owner === 'foreign') {
            if ($obj->isNew() || !empty($obj->_dirty)) {
                $saved= $obj->save();
            }
            $fk= $obj->get($foreign);
            if ($fk) {
                $this->set($local, $fk);
                if (isset($criteria['local']) && is_array($criteria['local'])) {
                    foreach ($criteria['local'] as $critKey => $critVal) {
                        if (is_numeric($critKey)) continue;
                        $this->set($critKey, $critVal);
                    }
                }
            }
        }
        if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG , ($saved ? 'Successfully saved' : 'Could not save') . " related object\nMain object: " . print_r($this->toArray('', true), true) . "\nRelated Object: " . print_r($obj->toArray('', true), true));
        return $saved;
    }

    /**
     * Remove the persistent instance of an object permanently.
     *
     * Deletes the persistent object instance stored in the database when
     * called, including any dependent objects defined by composite foreign key
     * relationships.
     *
     * @todo Implement some way to reassign ownership of related composite
     * objects when remove is called, perhaps by passing another object
     * instance as an optional parameter, or creating a separate method.
     *
     * @param array $ancestors Keeps track of instances which have already been
     * removed to prevent loops with circular references.
     * @return boolean Returns true on success, false on failure.
     */
    public function remove(array $ancestors= array ()) {
        $result= false;
        $pk= $this->getPrimaryKey();
        if ($pk && $this->xpdo->getConnection(array(xPDO::OPT_CONN_MUTABLE => true))) {
            $primaryKey = $pk;
            if (is_array($primaryKey)) {
                $primaryKey = implode('|', $primaryKey);
            }
            if (!empty ($this->_composites)) {
                if (!isset($ancestors[$this->_class])) {
                    $ancestors[$this->_class] = array();
                }
                if (in_array($primaryKey, $ancestors[$this->_class])) {
                    return false;
                }
                $ancestors[$this->_class][] = $primaryKey;
                foreach ($this->_composites as $compositeAlias => $composite) {
                    if (!isset($ancestors[$composite['class']])) {
                        $ancestors[$composite['class']] = array();
                    }
                    if ($composite['cardinality'] === 'many') {
                        if ($many= $this->getMany($compositeAlias)) {
                            /** @var xPDOObject $one */
                            foreach ($many as $one) {
                                $childPK = $one->getPrimaryKey();
                                if (is_array($childPK)) {
                                    $childPK = implode('|', $childPK);
                                }
                                if (in_array($childPK, $ancestors[$composite['class']])) {
                                    continue;
                                }
                                if (!$one->remove($ancestors)) {
                                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error removing dependent object: " . print_r($one->toArray('', true), true));
                                } else {
                                    $ancestors[$composite['class']][]= $childPK;
                                }
                            }
                            unset($many);
                        }
                    }
                    elseif ($one= $this->getOne($compositeAlias)) {
                        $childPK = $one->getPrimaryKey();
                        if (is_array($childPK)) {
                            $childPK = implode('|', $childPK);
                        }
                        if (in_array($childPK, $ancestors[$composite['class']])) {
                            continue;
                        }
                        if (!isset($ancestors[$composite['class']])) {
                            $ancestors[$composite['class']] = array();
                        }
                        if (!$one->remove($ancestors)) {
                            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error removing dependent object: " . print_r($one->toArray('', true), true));
                        } else {
                            $ancestors[$composite['class']][] = $childPK;
                        }
                        unset($one);
                    }
                }
            }
            $delete= $this->xpdo->newQuery($this->_class);
            $delete->command('DELETE');
            $delete->where($pk);
            // $delete->limit(1);
            $stmt= $delete->prepare();
            if (is_object($stmt)) {
                $tstart = microtime(true);
                if (!$result= $stmt->execute()) {
                    $this->xpdo->queryTime += microtime(true) - $tstart;
                    $this->xpdo->executedQueries++;
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not delete from ' . $this->_table . '; primary key specified was ' . print_r($pk, true) . "\n" . print_r($stmt->errorInfo(), true));
                } else {
                    $this->xpdo->queryTime += microtime(true) - $tstart;
                    $this->xpdo->executedQueries++;
                    $callback = $this->getOption(xPDO::OPT_CALLBACK_ON_REMOVE);
                    if ($callback && is_callable($callback)) {
                        call_user_func($callback, array('className' => $this->_class, 'criteria' => $delete, 'object' => $this));
                    }
                    if ($this->xpdo->_cacheEnabled && $this->xpdo->getOption('cache_db', null, false)) {
                        /** @var xPDOCache $dbCache */
                        $dbCache = $this->xpdo->getCacheManager()->getCacheProvider(
                            $this->getOption('cache_db_key', null, 'db'),
                            array(
                                xPDO::OPT_CACHE_KEY => $this->getOption('cache_db_key', null, 'db'),
                                xPDO::OPT_CACHE_HANDLER => $this->getOption(xPDO::OPT_CACHE_DB_HANDLER, null, $this->getOption(xPDO::OPT_CACHE_HANDLER, null, 'cache.xPDOFileCache')),
                                xPDO::OPT_CACHE_FORMAT => (integer) $this->getOption('cache_db_format', null, $this->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
                                xPDO::OPT_CACHE_EXPIRES => (integer) $this->getOption(xPDO::OPT_CACHE_DB_EXPIRES, null, $this->getOption(xPDO::OPT_CACHE_EXPIRES, null, 0)),
                                xPDO::OPT_CACHE_PREFIX => $this->getOption('cache_db_prefix', null, xPDOCacheManager::CACHE_DIR)
                            )
                        );
                        if (!$dbCache->delete($this->_class, array('multiple_object_delete' => true))) {
                            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "Could not remove cache entries for {$this->_class}", '', __METHOD__, __FILE__, __LINE__);
                        }
                    }
                    $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Removed {$this->_class} instance with primary key " . print_r($pk, true));
                }
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not build criteria to delete from ' . $this->_table . '; primary key specified was ' . print_r($pk, true));
            }
        }
        return $result;
    }

    /**
     * Gets the value (or values) of the primary key field(s) for the object.
     *
     * @param boolean $validateCompound If any of the keys in a compound primary key are empty
     * or null, and the default value is not allowed to be null, do not return an array, instead
     * return null; the default is true
     * @return mixed The string (or an array) representing the value(s) of the
     * primary key field(s) for this instance.
     */
    public function getPrimaryKey($validateCompound= true) {
        $value= null;
        if ($pk= $this->getPK()) {
            if (is_array($pk)) {
                foreach ($pk as $k) {
                    $_pk= $this->get($k);
                    if (($_pk && strtolower($_pk) !== 'null') || !$validateCompound) {
                        $value[]= $_pk;
                    } else {
                        if (isset ($this->_fieldMeta[$k]['default'])) {
                            $value[]= $this->_fieldMeta[$k]['default'];
                            $this->_fields[$k]= $this->_fieldMeta[$k]['default'];
                        }
                        elseif (isset ($this->_fieldMeta[$k]['null']) && $this->_fieldMeta[$k]['null']) {
                            $value[]= null;
                        }
                        else {
                            $value= null;
                            break;
                        }
                    }
                }
            } else {
                $value= $this->get($pk);
            }
        }
        if (!$value && $this->xpdo->getDebug() === true) {
            $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "No primary key value found for pk definition: " . print_r($this->getPK(), true));
        }
        return $value;
    }

    /**
     * Gets the name (or names) of the primary key field(s) for the object.
     *
     * @return mixed The string (or an array of strings) representing the name(s)
     * of the primary key field(s) for this instance.
     */
    public function getPK() {
        if ($this->_pk === null) {
            $this->_pk= $this->xpdo->getPK($this->_class);
        }
        return $this->_pk;
    }

    /**
     * Gets the type of the primary key field for the object.
     *
     * @return string The type of the primary key field for this instance.
     */
    public function getPKType() {
        if ($this->_pktype === null) {
            if ($this->_pk === null) {
                $this->getPK();
            }
            $this->_pktype= $this->xpdo->getPKType($this->_class);
        }
        return $this->_pktype;
    }

    /**
     * Get the name of a class related by foreign key to a specified field key.
     *
     * This is generally used to lookup classes involved in one-to-one
     * relationships with the current object.
     *
     * @param string $k The field name or key to lookup a related class for.
     */
    public function getFKClass($k) {
        $fkclass= null;
        $k = $this->getField($k, true);
        if (is_string($k)) {
            if (!empty ($this->_aggregates)) {
                foreach ($this->_aggregates as $aggregateAlias => $aggregate) {
                    if ($aggregate['local'] === $k) {
                        $fkclass= $aggregate['class'];
                        break;
                    }
                }
            }
            if (!$fkclass && !empty ($this->_composites)) {
                foreach ($this->_composites as $compositeAlias => $composite) {
                    if ($composite['local'] === $k) {
                        $fkclass= $composite['class'];
                        break;
                    }
                }
            }
            $fkclass= $this->xpdo->loadClass($fkclass);
        }
        if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Returning foreign key class {$fkclass} for column {$k}");
        return $fkclass;
    }

    /**
     * Get a foreign key definition for a specific classname.
     *
     * This is generally used to lookup classes in a one-to-many relationship
     * with the current object.
     *
     * @param string $alias Alias of the related class to lookup a foreign key
     * definition from.
     * @return array A foreign key definition.
     */
    public function getFKDefinition($alias) {
        return $this->xpdo->getFKDefinition($this->_class, $alias);
    }

    /**
     * Gets a field name as represented in the database container.
     *
     * This gets the name of the field, fully-qualified by either the object
     * table name or a specified alias, and properly quoted.
     *
     * @param string $k The simple name of the field.
     * @param string $alias An optional alias for the table in a specific query.
     * @return string The name of the field, qualified with the table name or an
     * optional table alias.
     */
    public function getFieldName($k, $alias= null) {
        if ($this->fieldNames === null) {
            $this->_initFields();
        }
        $name= null;
        $k = $this->getField($k, true);
        if (is_string($k) && isset ($this->fieldNames[$k])) {
            $name= $this->fieldNames[$k];
        }
        if ($name !== null && $alias !== null) {
            $name= str_replace("{$this->_table}.", "{$alias}.", $name);
        }
        return $name;
    }

    /**
     * Get a field name, looking up any by alias if not an actual field.
     *
     * @param string $key The field name or alias to translate to the actual field name.
     * @param bool $validate If true, the method will return false if the field or an alias
     * of it is not found. Otherwise, the key is returned as passed.
     * @return string|bool The actual field name, the key as passed, or false if not a field
     * or alias and validate is true.
     */
    public function getField($key, $validate = false) {
        $field = $key;
        if (!array_key_exists($key, $this->_fieldMeta)) {
            if (array_key_exists($key, $this->_fieldAliases)) {
                $field = $this->_fieldAliases[$key];
            } elseif ($validate === true) {
                $field = false;
            }
        }
        return $field;
    }

    /**
     * Load a graph of related objects to the current object.
     *
     * @param boolean|string|array|integer $graph An option to tell how to deal with related objects. If integer, will
     * traverse related objects up to a $graph level of depth and load them to the object.
     * If an array, will traverse required related object and load them to the object.
     * If true, will traverse the entire graph and append all related objects to the object (default behavior).
     * @param xPDOCriteria|array|string|integer $criteria A valid xPDO criteria representation.
     * @param boolean|integer $cacheFlag Indicates if the objects should be cached and optionally, by specifying an
     * integer value, for how many seconds.
     * @return array|boolean The graph that was loaded or false if nothing was loaded.
     */
    public function getGraph($graph = true, $criteria = null, $cacheFlag = true) {
        /* graph is true, get all relations to max depth */
        if ($graph === true) {
            $graph = $this->xpdo->getGraph($this->_class);
        }
        /* graph is an int, get relations to depth of graph */
        if (is_int($graph)) {
            $graph = $this->xpdo->getGraph($this->_class, $graph);
        }
        /* graph defined as JSON, convert to array */
        if (is_string($graph)) {
            $graph= $this->xpdo->fromJSON($graph);
        }
        /* graph as an array */
        if (is_array($graph)) {
            foreach ($graph as $alias => $branch) {
                $fkMeta = $this->getFKDefinition($alias);
                if ($fkMeta) {
                    $fkCriteria = isset($fkMeta['criteria']) && isset($fkMeta['criteria']['foreign']) ? $fkMeta['criteria']['foreign'] : null;
                    if ($criteria === null) {
                        $query= array($fkMeta['foreign'] => $this->get($fkMeta['local']));
                        if ($fkCriteria !== null) {
                            $query= array($fkCriteria, $query);
                        }
                    } else {
                        $query= $this->xpdo->newQuery($fkMeta['class'], $criteria);
                        $addCriteria= array("{$query->getAlias()}.{$fkMeta['foreign']}" => $this->get($fkMeta['local']));
                        if ($fkCriteria !== null) {
                            $fkAddCriteria = array();
                            foreach ($fkCriteria as $fkCritKey => $fkCritVal) {
                                if (is_numeric($fkCritKey)) continue;
                                $fkAddCriteria["{$criteria->getAlias()}.{$fkCritKey}"] = $fkCritVal;
                            }
                            if (!empty($fkAddCriteria)) {
                                $addCriteria = array($fkAddCriteria, $addCriteria);
                            }
                        }
                        $query->andCondition($addCriteria);
                    }
                    $collection = $this->xpdo->call($fkMeta['class'], 'loadCollectionGraph', array(
                        &$this->xpdo,
                        $fkMeta['class'],
                        $branch,
                        $query,
                        $cacheFlag
                    ));
                    if (!empty($collection)) {
                        if ($fkMeta['cardinality'] == 'many') {
                            $this->_relatedObjects[$alias] = $collection;
                        } else {
                            $this->_relatedObjects[$alias] = reset($collection);
                        }
                    }
                }
            }
        } else {
            $graph = false;
        }
        return $graph;
    }

    /**
     * Copies the object fields and corresponding values to an associative array.
     *
     * @param string $keyPrefix An optional prefix to prepend to the field values.
     * @param boolean $rawValues An optional flag indicating if you want the raw values instead of
     * those returned by the {@link xPDOObject::get()} function.
     * @param boolean $excludeLazy An option flag indicating if you want to exclude lazy fields from
     * the resulting array; the default behavior is to include them which means the object will
     * query the database for the lazy fields before providing the value.
     * @param boolean|integer|string|array $includeRelated Describes if and how to include loaded related object fields.
     * As an integer all loaded related objects in the graph up to that level of depth will be included.
     * As a string, only loaded related objects matching the JSON graph representation will be included.
     * As an array, only loaded related objects matching the graph array will be included.
     * As boolean true, all currently loaded related objects will be included.
     * @return array An array representation of the object fields/values.
     */
    public function toArray($keyPrefix= '', $rawValues= false, $excludeLazy= false, $includeRelated= false) {
        $objArray= null;
        if (is_array($this->_fields)) {
            $keys= array_keys($this->_fields);
            if (!$excludeLazy && $this->isLazy()) {
                $this->_loadFieldData($this->_lazy);
            }
            foreach ($keys as $key) {
                if (!$excludeLazy || !$this->isLazy($key)) {
                    $objArray[$keyPrefix . $key]= $rawValues ? $this->_fields[$key] : $this->get($key);
                }
            }
        }
        if (!empty($includeRelated)) {
            $graph = null;
            if (is_int($includeRelated) && $includeRelated > 0) {
                $graph = $this->xpdo->getGraph($this->_class, $includeRelated);
            } elseif (is_string($includeRelated)) {
                $graph = $this->xpdo->fromJSON($includeRelated);
            } elseif (is_array($includeRelated)) {
                $graph = $includeRelated;
            }
            if ($includeRelated === true || is_array($graph)) {
                foreach ($this->_relatedObjects as $alias => $branch) {
                    if ($includeRelated === true || array_key_exists($alias, $graph)) {
                        if (is_array($branch)){
                            foreach($branch as $pk => $obj){
                                $objArray[$alias][$pk] = $obj->toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated === true ? true : $graph[$alias]);
                            }
                        } elseif ($branch instanceof xPDOObject) {
                            $objArray[$alias] = $branch->toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated === true ? true : $graph[$alias]);
                        }
                    }
                }
            }
        }
        return $objArray;
    }

    /**
     * Sets object fields from an associative array of key => value pairs.
     *
     * @param array $fldarray An associative array of key => values.
     * @param string $keyPrefix Specify an optional prefix to strip from all array
     * keys in fldarray.
     * @param boolean $setPrimaryKeys Optional param to set generated primary keys.
     * @param boolean $rawValues Optional way to set values without calling the
     * {@link xPDOObject::set()} method.
     * @param boolean $adhocValues Optional way to set adhoc values so that all the values of
     * fldarray become object vars.
     */
    public function fromArray($fldarray, $keyPrefix= '', $setPrimaryKeys= false, $rawValues= false, $adhocValues= false) {
        if (is_array($fldarray)) {
            $pkSet= false;
            $generatedKey= false;
            foreach ($fldarray as $key => $val) {
                if (!empty ($keyPrefix)) {
                    $prefixPos= strpos($key, $keyPrefix);
                    if ($prefixPos === 0) {
                        $key= substr($key, strlen($keyPrefix));
                    } else {
                        continue;
                    }
                    if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Stripped prefix {$keyPrefix} to produce key {$key}");
                }
                $key = $this->getField($key);
                if (isset ($this->_fieldMeta[$key]['index']) && $this->_fieldMeta[$key]['index'] == 'pk') {
                    if ($setPrimaryKeys) {
                        if (isset ($this->_fieldMeta[$key]['generated'])) {
                            $generatedKey= true;
                        }
                        if ($this->_new) {
                            if ($rawValues || $generatedKey) {
                                $this->_setRaw($key, $val);
                            } else {
                                $this->set($key, $val);
                            }
                            $pkSet= true;
                        }
                    }
                }
                elseif (isset ($this->_fieldMeta[$key])) {
                    if ($rawValues) {
                        $this->_setRaw($key, $val);
                    } else {
                        $this->set($key, $val);
                    }
                }
                elseif ($adhocValues || $this->getOption(xPDO::OPT_HYDRATE_ADHOC_FIELDS)) {
                    if ($rawValues) {
                        $this->_setRaw($key, $val);
                    } else {
                        $this->set($key, $val);
                    }
                }
                if ($this->isLazy($key)) {
                    $this->_lazy = array_diff($this->_lazy, array($key));
                }
            }
        }
    }

    /**
     * Add a validation rule to an object field for this instance.
     *
     * @param string $field The field key to apply the rule to.
     * @param string $name A name to identify the rule.
     * @param string $type The type of rule.
     * @param string $rule The rule definition.
     * @param array $parameters Any input parameters for the rule.
     */
    public function addValidationRule($field, $name, $type, $rule, array $parameters= array()) {
        $field = $this->getField($field);
        if (is_string($field)) {
            if (!$this->_validationLoaded) $this->_loadValidation();
            if (!isset($this->_validationRules[$field])) $this->_validationRules[$field]= array();
            $this->_validationRules[$field][$name]= array(
                'type' => $type,
                'rule' => $rule,
                'parameters' => array()
            );
            foreach ($parameters as $paramKey => $paramValue) {
                $this->_validationRules[$field][$name]['parameters'][$paramKey]= $paramValue;
            }
        }
    }

    /**
     * Remove one or more validation rules from this instance.
     *
     * @param string $field An optional field name to remove rules from. If not
     * specified or null, all rules from all columns will be removed.
     * @param array $rules An optional array of rule names to remove if a single
     * field is specified.  If $field is null, this parameter is ignored.
     */
    public function removeValidationRules($field = null, array $rules = array()) {
        if (!$this->_validationLoaded) $this->_loadValidation();
        if (empty($rules) && is_string($field)) {
            unset($this->_validationRules[$this->getField($field)]);
        } elseif (empty($rules) && is_null($field)) {
            $this->_validationRules = array();
        } elseif (is_array($rules) && !empty($rules) && is_string($field)) {
            $field = $this->getField($field);
            foreach ($rules as $name) {
                unset($this->_validationRules[$field][$name]);
            }
        }
    }

    /**
     * Get the xPDOValidator class configured for this instance.
     *
     * @return string|boolean The xPDOValidator instance or false if it could
     * not be loaded.
     */
    public function getValidator() {
        if (!is_object($this->_validator)) {
            $validatorClass = $this->xpdo->loadClass('validation.xPDOValidator', XPDO_CORE_PATH, true, true);
            if ($derivedClass = $this->getOption(xPDO::OPT_VALIDATOR_CLASS, null, '')) {
                if ($derivedClass = $this->xpdo->loadClass($derivedClass, '', false, true)) {
                    $validatorClass = $derivedClass;
                }
            }
            if ($validatorClass) {
                $this->_validator= new $validatorClass($this);
            }
        }
        return $this->_validator;
    }

    /**
     * Used to load validation from the object map.
     *
     * @access public
     * @param boolean $reload Indicates if the schema validation rules should be
     * reloaded.
     */
    public function _loadValidation($reload= false) {
        if (!$this->_validationLoaded || $reload == true) {
            $validation= $this->xpdo->getValidationRules($this->_class);
            $this->_validationLoaded= true;
            foreach ($validation as $column => $rules) {
                foreach ($rules as $name => $rule) {
                    $parameters = array_diff($rule, array($rule['type'], $rule['rule']));
                    $this->addValidationRule($column, $name, $rule['type'], $rule['rule'], $parameters);
                }
            }
        }
    }

    /**
     * Validate the field values using an xPDOValidator.
     *
     * @param array $options An array of options to pass to the validator.
     * @return boolean True if validation was successful.
     */
    public function validate(array $options = array()) {
        $validated= false;
        if ($validator= $this->getValidator()) {
            $validated= $this->_validator->validate($options);
            if ($this->xpdo->getDebug() === true) {
                $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Validator class executed, result = " . print_r($validated, true));
            }
        } else {
            if ($this->xpdo->getDebug() === true) {
                $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "No validator found for {$this->_class} instance.");
            }
            $validated= true;
        }
        return $validated;
    }

    /**
     * Indicates if the object or specified field has been validated.
     *
     * @param string $key Optional key to check for specific validation.
     * @return boolean True if the object or specified field has been fully
     * validated successfully.
     */
    public function isValidated($key= '') {
        $unvalidated = array_diff($this->_dirty, $this->_validated);
        if (empty($key)) {
            $validated = (count($unvalidated) > 0);
        } else {
            $validated = !in_array($this->getField($key), $unvalidated);
        }
        return $validated;
    }

    /**
     * Indicates if the object or specified field is lazy.
     *
     * @param string $key Optional key to check for laziness.
     * @return boolean True if the field specified or if any field is lazy if no
     * field is specified.
     */
    public function isLazy($key= '') {
        $lazy = false;
        if (empty($key)) {
            $lazy = (count($this->_lazy) > 0);
        } else {
            $key = $this->getField($key, true);
            if ($key !== false) {
                $lazy = in_array($key, $this->_lazy);
            }
        }
        return $lazy;
    }

    /**
     * Gets related objects by a foreign key and specified criteria.
     *
     * @access protected
     * @param string $alias The alias representing the relationship.
     * @param mixed An optional xPDO criteria expression.
     * @param boolean|integer Indicates if the saved object(s) should
     * be cached and optionally, by specifying an integer value, for how many
     * seconds before expiring.  Overrides the cacheFlag for the object.
     * @return array A collection of objects matching the criteria.
     */
    protected function & _getRelatedObjectsByFK($alias, $criteria= null, $cacheFlag= true) {
        $collection= array ();
        if (isset($this->_relatedObjects[$alias]) && (is_object($this->_relatedObjects[$alias]) || (is_array($this->_relatedObjects[$alias]) && !empty ($this->_relatedObjects[$alias])))) {
            $collection= & $this->_relatedObjects[$alias];
        } else {
            $fkMeta= $this->getFKDefinition($alias);
            if ($fkMeta) {
                $fkCriteria = isset($fkMeta['criteria']) && isset($fkMeta['criteria']['foreign']) ? $fkMeta['criteria']['foreign'] : null;
                if ($criteria === null) {
                    $criteria= array($fkMeta['foreign'] => $this->get($fkMeta['local']));
                    if ($fkCriteria !== null) {
                        $criteria= array($fkCriteria, $criteria);
                    }
                } else {
                    $criteria= $this->xpdo->newQuery($fkMeta['class'], $criteria);
                    $addCriteria = array("{$criteria->getAlias()}.{$fkMeta['foreign']}" => $this->get($fkMeta['local']));
                    if ($fkCriteria !== null) {
                        $fkAddCriteria = array();
                        foreach ($fkCriteria as $fkCritKey => $fkCritVal) {
                            if (is_numeric($fkCritKey)) continue;
                            $fkAddCriteria["{$criteria->getAlias()}.{$fkCritKey}"] = $fkCritVal;
                        }
                        if (!empty($fkAddCriteria)) {
                            $addCriteria = array($fkAddCriteria, $addCriteria);
                        }
                    }
                    $criteria->andCondition($addCriteria);
                }
                if ($collection= $this->xpdo->getCollection($fkMeta['class'], $criteria, $cacheFlag)) {
                    $this->_relatedObjects[$alias]= array_diff_key($this->_relatedObjects[$alias], $collection) + $collection;
                }
            }
        }
        if ($this->xpdo->getDebug() === true) {
            $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "_getRelatedObjectsByFK :: {$alias} :: " . (is_object($criteria) ? print_r($criteria->sql, true)."\n".print_r($criteria->bindings, true) : 'no criteria'));
        }
        return $collection;
    }

    /**
     * Initializes the field names with the qualified table name.
     *
     * Once this is called, you can lookup the qualified name by the field name
     * itself in {@link xPDOObject::$fieldNames}.
     *
     * @access protected
     */
    protected function _initFields() {
        foreach ($this->_fieldMeta as $k => $v) {
            $this->fieldNames[$k]= $this->xpdo->escape($this->_table) . '.' . $this->xpdo->escape($k);
        }
    }

    /**
     * Returns a JSON representation of the object.
     *
     * @param string $keyPrefix An optional prefix to prepend to the field keys.
     * @param boolean $rawValues An optional flag indicating if the field values
     * should be returned raw or via {@link xPDOObject::get()}.
     * @return string A JSON string representing the object.
     */
    public function toJSON($keyPrefix= '', $rawValues= false) {
        $json= '';
        $array= $this->toArray($keyPrefix, $rawValues);
        if ($array) {
            $json= $this->xpdo->toJSON($array);
        }
        return $json;
    }

    /**
     * Sets the object fields from a JSON object string.
     *
     * @param string $jsonSource A JSON object string.
     * @param string $keyPrefix An optional prefix to strip from the keys.
     * @param boolean $setPrimaryKeys Indicates if primary key fields should be set.
     * @param boolean $rawValues Indicates if values should be set raw or via
     * {@link xPDOObject::set()}.
     * @param boolean $adhocValues Indicates if ad hoc fields should be added to the
     * xPDOObject from the source object.
     */
    public function fromJSON($jsonSource, $keyPrefix= '', $setPrimaryKeys= false, $rawValues= false, $adhocValues= false) {
        $array= $this->xpdo->fromJSON($jsonSource, true);
        if ($array) {
            $this->fromArray($array, $keyPrefix, $setPrimaryKeys, $rawValues, $adhocValues);
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'xPDOObject::fromJSON() -- Could not convert jsonSource to a PHP array.');
        }
    }

    /**
     * Encodes a string using the specified algorithm.
     *
     * NOTE: This implementation currently only implements md5.  To implement additional
     * algorithms, override this function in your xPDOObject derivative classes.
     *
     * @param string $source The string source to encode.
     * @param string $type The type of encoding algorithm to apply, md5 by default.
     * @return string The encoded string.
     */
    public function encode($source, $type= 'md5') {
        if (!is_string($source) || empty ($source)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'xPDOObject::encode() -- Attempt to encode source data that is not a string (or is empty); encoding skipped.');
            return $source;
        }
        switch ($type) {
            case 'password':
            case 'md5':
                $encoded= md5($source);
                break;
            default :
                $encoded= $source;
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOObject::encode() -- Attempt to encode source data using an unsupported encoding algorithm ({$type}).");
                break;
        }
        return $encoded;
    }

    /**
     * Indicates if an object field has been modified (or never saved).
     *
     * @access public
     * @param string $key The field name to check.
     * @return boolean True if the field exists and either has been modified or the object is new.
     */
    public function isDirty($key) {
        $dirty= false;
        $actualKey = $this->getField($key, true);
        if ($actualKey !== false) {
            if (array_key_exists($actualKey, $this->_dirty) || $this->isNew()) {
                $dirty= true;
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOObject::isDirty() -- Attempt to check if an unknown field ({$key}) has been modified.");
        }
        return $dirty;
    }

    /**
     * Add the field to a collection of field keys that have been modified.
     *
     * This function also clears any validation flag associated with the field.
     *
     * @param string $key The key of the field to set dirty.
     */
    public function setDirty($key= '') {
        if (empty($key)) {
            foreach (array_keys($this->_fieldMeta) as $fIdx => $fieldKey) {
                $this->setDirty($fieldKey);
            }
        }
        else {
            $key = $this->getField($key, true);
            if ($key !== false) {
                $this->_dirty[$key] = $key;
                if (isset($this->_validated[$key])) unset($this->_validated[$key]);
            }
        }
    }

    /**
     * Indicates if the instance is new, and has not yet been persisted.
     *
     * @return boolean True if the object has not been saved or was loaded from
     * the database.
     */
    public function isNew() {
        return (boolean) $this->_new;
    }

    /**
     * Gets the database data type for the specified field.
     *
     * @access protected
     * @param string $key The field name to get the data type for.
     * @return string The DB data type of the field.
     */
    protected function _getDataType($key) {
        $type= 'text';
        $actualKey = $this->getField($key, true);
        if ($actualKey !== false && isset($this->_fieldMeta[$actualKey]['dbtype'])) {
            $type= strtolower($this->_fieldMeta[$actualKey]['dbtype']);
        } elseif ($this->xpdo->getDebug() === true) {
            $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "xPDOObject::_getDataType() -- No data type specified for field ({$key}), using `text`.");
        }
        return $type;
    }

    /**
     * Gets the php data type for the specified field.
     *
     * @access protected
     * @param string $key The field name to get the data type for.
     * @return string The PHP data type of the field.
     */
    protected function _getPHPType($key) {
        $type= 'string';
        $actualKey = $this->getField($key, true);
        if ($actualKey !== false && isset($this->_fieldMeta[$actualKey]['phptype'])) {
            $type= strtolower($this->_fieldMeta[$actualKey]['phptype']);
        } elseif ($this->xpdo->getDebug() === true) {
            $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "xPDOObject::_getPHPType() -- No PHP type specified for field ({$key}), using `string`.");
        }
        return $type;
    }

    /**
     * Load persistent data from the source for the field(s) indicated.
     *
     * @access protected
     * @param string|array $fields A field name or array of field names to load
     * from the data source.
     */
    protected function _loadFieldData($fields) {
        if (!is_array($fields)) $fields= array($fields);
        else $fields= array_values($fields);
        $criteria= $this->xpdo->newQuery($this->_class, $this->getPrimaryKey());
        $criteria->select($fields);
        if ($rows= xPDOObject :: _loadRows($this->xpdo, $this->_class, $criteria)) {
            $row= $rows->fetch(PDO::FETCH_ASSOC);
            $rows->closeCursor();
            $this->fromArray($row, '', false, true);
            $this->_lazy= array_diff($this->_lazy, $fields);
        }
    }

    /**
     * Set a raw value on a field converted to the appropriate type.
     *
     * @access protected
     * @param string $key The key identifying the field to set.
     * @param mixed $val The value to set.
     * @return boolean Returns true if the value was set, false otherwise.
     */
    protected function _setRaw($key, $val) {
        $set = false;
        if ($val === null) {
            $this->_fields[$key] = null;
            $set = true;
        } else {
            $phptype = $this->_getPHPType($key);
            $dbtype = $this->_getDataType($key);
            switch ($phptype) {
                case 'int':
                case 'integer':
                case 'boolean':
                    $this->_fields[$key] = (integer) $val;
                    $set = true;
                    break;
                case 'float':
                    $this->_fields[$key] = (float) $val;
                    $set = true;
                    break;
                case 'array':
                    if (is_array($val)) {
                        $this->_fields[$key]= serialize($val);
                        $set = true;
                    } elseif (is_string($val)) {
                        $this->_fields[$key]= $val;
                        $set = true;
                    } elseif (is_object($val) && $val instanceof xPDOObject) {
                        $this->_fields[$key]= serialize($val->toArray());
                        $set = true;
                    }
                    break;
                case 'json':
                    if (!is_string($val)) {
                        $v = $val;
                        if (is_array($v)) {
                            $this->_fields[$key] = $this->xpdo->toJSON($v);
                            $set = true;
                        } elseif (is_object($v) && $v instanceof xPDOObject) {
                            $this->_fields[$key] = $this->xpdo->toJSON($v->toArray());
                            $set = true;
                        }
                    } else {
                        $this->_fields[$key]= $val;
                        $set = true;
                    }
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                    if (preg_match('/int/i', $dbtype)) {
                        $this->_fields[$key] = (integer) $val;
                        $set = true;
                        break;
                    }
                default:
                    $this->_fields[$key] = $val;
                    $set = true;
            }
        }
        if ($set) $this->setDirty($key);
        return $set;
    }

    /**
     * Find aliases for any defined object relations of the specified class.
     *
     * @access protected
     * @param string $class The name of the class to find aliases from.
     * @param int $limit An optional limit on the number of aliases to return;
     * default is 0, i.e. no limit.
     * @return array An array of aliases or an empty array if none are found.
     */
    protected function _getAliases($class, $limit = 0) {
        $aliases = array();
        $limit = intval($limit);
        $array = array('aggregates' => $this->_aggregates, 'composites' => $this->_composites);
        foreach ($array as $relType => $relations) {
            foreach ($relations as $alias => $def) {
                if (isset($def['class']) && $def['class'] == $class) {
                    $aliases[] = $alias;
                    if ($limit > 0 && count($aliases) > $limit) break;
                }
            }
        }
        return $aliases;
    }
}

/**
 * Extend to define a class with a native integer primary key field named id.
 *
 * @see xpdo/om/mysql/xpdosimpleobject.map.inc.php
 * @package xpdo
 * @subpackage om
 */
class xPDOSimpleObject extends xPDOObject {}
