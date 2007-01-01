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
 * The base persistent XPDO object classes.
 * 
 * This file contains the base persistent object classes for MySQL, which your
 * user-defined classes will extend when implementing your XPDO object model
 * for the MySQL platform.
 * 
 * @package xpdo.om.mysql
 */

if (!defined('ADODB_ALLOW_NEGATIVE_TS')) {
    define('ADODB_ALLOW_NEGATIVE_TS', 1);
}

/**
 * The base persistent XPDO object class.
 * 
 * This is the basis for the entire OpenExpedio object model, and can also be
 * used by a class generator {@see xPDOGenerator}, ultimately allowing custom
 * classes to be user-defined in a web interface and framework-generated at
 * runtime.
 * 
 * @abstract This is an abstract class, and is not represented by an actual
 * table; it simply defines the member variables and functions needed for object
 * persistence.
 * @package xpdo.om.mysql
 */
class xPDOObject {
    /**
     * @var xPDO A convenience reference to the xPDO object.
     * @access public
     */
    var $xpdo= null;

    /**
     * @var string Name of the data source container the object belongs to.
     * @access public
     */
    var $container= null;
    /**
     * @var array Names of the fields in the data table, fully-qualified with a
     * table name.  For use in table joins to qualify fields with the same name.
     * @access public
     */
    var $fieldNames= null;
    /**
     * @var string|array The primary key field name (or an array of primary key
     * field names) for this object.
     * @access protected
     */
    var $_pk= null;
    /**
     * @var string The php native type of the primary key field.  Will be set to
     * 'compound' if multiple primary keys are specified for the object.
     * @access protected
     */
    var $_pktype= null;
    /**
     * @var string Name of the actual table representing this class.
     * @access protected
     */
    var $_table= null;
    /**
     * @var string The type of table representing the class (e.g. MyISAM or
     * InnoDB).
     * @access protected
     */
    var $_tableType= null;
    /**
     * @var array An array of field names that have been changed since the
     * instance was last persisted.
     * @access protected
     */
    var $_dirty= array ();
    /**
     * @var array An array of key-value pairs representing the fields of the
     * instance.
     * @access protected
     */
    var $_fields= array ();
    /**
     * @var array An array of metadata definitions for each field in the class.
     * @access protected
     */
    var $_fieldMeta= array ();
    /**
     * @var array An array of aggregate foreign key relationships for the class.
     * @access protected
     */
    var $_aggregates= array ();
    /**
     * @var array An array of composite foreign key relationships for the class.
     * @access protected
     */
    var $_composites= array ();
    /**
     * @var array An array of object instances related to this object instance
     * by an aggregate or composite relationship.
     * @access protected
     */
    var $_relatedObjects= array ();

    /**
     * @var boolean Indicates if the instance is transient (and thus new).
     * @access protected
     */
    var $_new= true;
    var $_cacheFlag= false;
    var $_currentTimestamps= array (
        'CURRENT_TIMESTAMP',
        'CURRENT_TIMESTAMP()',
        'NOW()', 
        'LOCALTIME',
        'LOCALTIME()',
        'LOCALTIMESTAMP', 
        'LOCALTIMESTAMP()',
        'SYSDATE()',
    );
    var $_currentDates= array (
        'CURDATE()',
        'CURRENT_DATE',
        'CURRENT_DATE()',
    );

    /**
     * Constructor (do not call directly; {@see xPDO::newObject()}).
     * 
     * @access protected
     */
    public function xPDOObject(& $xpdo) {
        $this->container= $xpdo->config['dbname'];
        $this->_table= $xpdo->getTableName(get_class($this));
        $this->_tableType= $xpdo->getTableType(get_class($this));
        $this->_fields= $xpdo->getFields(get_class($this));
        $this->_fieldMeta= $xpdo->getFieldMeta(get_class($this));
        $this->_aggregates= $xpdo->getAggregates(get_class($this));
        $this->_composites= $xpdo->getComposites(get_class($this));
        $this->xpdo= & $xpdo;
    }

    /**
     * Set a field value by the field key or name.
     * 
     * @todo Handle all type conversions properly.
     * @todo Define and implement field validation.
     * @param string $k The field key or name.
     * @param mixed $v The value to set the field to.
     * @return boolean Determines whether the value was set successfully and was
     * determined to be dirty (i.e. different from the previous value).
     */
    function set($k, $v= null, $vType= '') {
        $set= false;
        if (isset ($this->_fieldMeta[$k])) {
            $oldValue= $this->_fields[$k];
            if ($oldValue !== $v) {
                $fkclass= '';
                if (isset ($this->_fieldMeta[$k]['index'])) {
                    if ($this->_fieldMeta[$k]['index'] === 'pk' && isset ($this->_fieldMeta[$k]['generated'])) {
                        return false;
                    }
                }
                //type validation
                $phptype= $this->_fieldMeta[$k]['phptype'];
                $dbtype= $this->_fieldMeta[$k]['dbtype'];
                switch ($phptype) {
                    case 'timestamp' :
                        if ($dbtype == 'int' || $dbtype == 'integer' || $dbtype == 'INT' || $dbtype == 'INTEGER') {
                            if ($v === 'UNIX_TIMESTAMP()' || $v === 'NULL' || $v === '0' || $v === 0) {
                                $this->_fields[$k]= $v;
                                $set= true;
                            } else {
                                $v= $vType == 'integer' ? intval($v) : $this->strtotime($v);
                                if ($v != -1 && $v != false && intval($v)) {
                                    $this->_fields[$k]= $v;
                                    $set= true;
                                }
                            }
                        } else {
                            $ts= false;
                            if (in_array($v, $this->_currentTimestamps) || $v === 'NULL' || $v === '0000-00-00 00:00:00') {
                                $this->_fields[$k]= $v;
                                $set= true;
                            } elseif ($vType == 'integer') {
                                $ts= intval($v);
                            } elseif (is_string($v) && !empty($v)) {
                                $ts= $this->strtotime($v);
                            }
                            if ($ts != -1 && $ts !== false) {
                                $this->_fields[$k]= $this->strftime('%Y-%m-%d %H:%M:%S', $ts);
                                $set= true;
                            }
                        }
                        break;
                    case 'datetime' :
                        if (in_array($v, $this->_currentTimestamps) || $v === 'NULL' || $v === '0000-00-00 00:00:00') {
                            $this->_fields[$k]= $v;
                            $set= true;
                        } elseif ($v) {
                            $ts= $this->strtotime($v);
                            if ($ts !== -1 && $ts !== false) {
                                $this->_fields[$k]= $this->strftime('%Y-%m-%d %H:%M:%S', $ts);
                                $set= true;
                            }
                        }
                        break;
                    case 'date' :
                        if (in_array($v, $this->_currentDates) || $v === 'NULL' || $v === '0000-00-00') {
                            $this->_fields[$k]= $v;
                            $set= true;
                        } elseif ($v) {
                            $ts= strtotime($v);
                            if ($ts !== -1 && $ts !== false) {
                                $this->_fields[$k]= $this->strftime('%Y-%m-%d', $ts);
                                $set= true;
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
                    default :
                        $this->_fields[$k]= (string) $v;
                        $set= true;
                }
                //@todo add custom validation here?
                if ($oldValue !== $this->_fields[$k]) {
                    $this->_dirty[$k]= $k;
                } else {
                    $set= false;
                }
            }
        }
        return $set;
    }

    /**
     * Get a field value (or a set of values) by the field key(s) or name(s).
     * 
     * <strong>Warning</strong>: do not use the $format parameter if retrieving
     * multiple values of different types, as the format string will be applied
     * to all types, most likely with unpredicatable results.  Optionally, you
     * can supply an associate array of format strings with the field key as the
     * key for the format array.
     * 
     * @param mixed $k A string (or an array of strings) representing the field
     * key or name.
     * @param string $format An optional formatting string to control the return
     * value(s).
     * @return mixed The value(s) of the field(s) requested, optionally formatted
     * by a format string as appropriate for each field type.
     */
    function get($k, $format= null, $formatTemplate= null) {
        $value= null;
        if (is_array($k)) {
            foreach ($k as $key) {
                if (isset ($this->_fieldMeta[$key])) {
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
        } else {
            if (isset ($this->_fields[$k]) && isset ($this->_fieldMeta[$k]['phptype']) && isset ($this->_fieldMeta[$k]['dbtype'])) {
                $dbType= $this->_fieldMeta[$k]['dbtype'];
                $fieldType= $this->_fieldMeta[$k]['phptype'];
                $value= $this->_fields[$k];
                if ($value === 'NULL' || $value === null) {
                    $value= null;
                } else {
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
                            if ($dbType == 'int' || $dbType == 'integer' || $dbType == 'INT' || $dbType == 'INTEGER') {
                                if (intval($value) > 0) {
                                    $value= $this->strftime('%Y-%m-%d %H:%M:%S', $value);
                                } else {
                                    $value= $this->strftime('%Y-%m-%d %H:%M:%S');
                                }
                            } elseif (in_array($value, $this->_currentTimestamps)) {
                                $value= $this->strftime('%Y-%m-%d %H:%M:%S');
                            }
                            elseif (is_string($format) && !empty ($format)) {
                                if (strpos($format, 're:') === 0) {
                                    if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                        $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                    }
                                } elseif (strpos($format, '%') === false) {
                                    $value= $this->date2($format, $value);
                                } elseif ($ts= $this->strtotime($value) != -1 && $ts !== false) {
                                    $value= $this->strftime($format, $ts);
                                }
                            }
                            break;
                        case 'datetime' :
                            if (in_array($value, $this->_currentTimestamps)) {
                                $value= $this->strftime('%Y-%m-%d %H:%M:%S');
                            }
                            elseif (is_string($format) && !empty ($format)) {
                                if (strpos($format, 're:') === 0) {
                                    if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                        $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                    }
                                } elseif (strpos($format, '%') === false) {
                                    $value= $this->date2($format, $value);
                                } elseif ($ts= $this->strtotime($value) != -1 && $ts !== false) {
                                    $value= $this->strftime($format, $ts);
                                }
                            }
                            break;
                        case 'date' :
                            if (in_array($value, $this->_currentDates)) {
                                $value= $this->strftime('%Y-%m-%d');
                            }
                            elseif (is_string($format) && !empty ($format)) {
                                if (strpos($format, 're:') === 0) {
                                    if (!empty ($formatTemplate) && is_string($formatTemplate)) {
                                        $value= preg_replace(substr($format, 3), $formatTemplate, $value);
                                    }
                                } elseif (strpos($format, '%') === false) {
                                    $value= $this->date2($format, $value);
                                } elseif ($ts= $this->strtotime($value) != -1 && $ts !== false) {
                                    $value= $this->strftime($format, $ts);
                                }
                            }
                            break;
                        default :
//                            $value= stripslashes($value);
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
     * @see getMany()
     * @see addOne()
     * @see addMany()
     * 
     * @param string $class Name of the foreign class representing the related
     * object.
     * @param string $key The key of the field representing the foreign key.
     * @param object $criteria xPDOCriteria object to get the related objects
     * @param boolean|integer $cacheFlag Indicates if the object should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     */
    function & getOne($class, $key= '', $criteria= null, $cacheFlag= false) {
        $object= null;
        if ($fkdef= $this->getFKDefinition($class, $key)) {
            if (empty ($key)) {
                $key= $fkdef['key'];
            }
            $k= $fkdef['local'];
            $fk= $fkdef['foreign'];
            if (isset ($this->_relatedObjects[$class][$key])) {
                if (is_object($this->_relatedObjects[$class][$key])) {
                    $object= & $this->_relatedObjects[$class][$key];
                }
            }
            if (!$object) {
                if ($criteria === null) {
                    $fktable= $this->xpdo->getTableName($class);
                    $fkvalue= $this->get($k);
                    $fktype= $this->_fieldMeta[$k]['phptype'] == 'integer' ? PDO_PARAM_INT : PDO_PARAM_STR;
                    $criteria= new xPDOCriteria($this->xpdo, "SELECT * FROM {$fktable} WHERE {$fk} = :{$fk}");
                    $criteria->bind(array (":{$fk}" => array('value' => $fkvalue, 'type' => $fktype)), true);
                }
                if ($object= $this->xpdo->getObject($class, $criteria, $cacheFlag)) {
                    $this->_relatedObjects[$class][$key]= & $object;
                }
            }
        } else {
            $this->xpdo->_log(XPDO_LOG_LEVEL_WARN, "Could not getOne: foreign key definition for class {$class}, key {$key} not found.");
        }
        return $object;
    }

    /**
     * Gets a collection of related objects where the fk is defined in an
     * external entity, i.e. for many-to-many relationships.
     * 
     * @see getOne()
     * @see addOne()
     * @see addMany()
     * 
     * @param string $class Name of the foreign class representing the related
     * object.
     * @param object $criteria xPDOCriteria object to get the related objects
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     */
    function & getMany($class, $fk= '', $criteria= null, $cacheFlag= false) {
        $collection= null;
        $key= false;
        if ($fkMeta= $this->getFKDefinition($class, $fk)) {
            if (empty ($fk)) {
                $key= $fkMeta['key'];
            }
        }
        if ($key) {
            $collection= $this->_getRelatedObjectsByFK($class, $key, $criteria, $cacheFlag);
        }
        return $collection;
    }

    /**
     * Adds an object related to this instance by a foreign key relationship.
     * 
     * @see getOne()
     * @see getMany()
     * @see addMany()
     * 
     * @param mixed $obj A single object to be related to this instance.
     * @param string $k The key of the field representing the foreign key
     * (only required if more than one FK is related to the same class).
     * @return boolean True if the related object was added to this object.
     */
    function addOne(& $obj, $k= '') {
        $added= false;
        if (is_object($obj)) {
            $objclass= get_class($obj);
            $fkMeta= $this->getFKDefinition($objclass, $k);
            if ($fkMeta && $fkMeta['cardinality'] === 'one') {
                if (empty ($k)) {
                    $k= $fkMeta['key'];
                }
                $fk= $fkMeta['foreign'];
                $key= $fkMeta['local'];
                $kval= $this->get($key);
                $fkval= $obj->get($fk);
                if ($k === $fk) {
                    $obj->set($k, $kval);
                    $this->_relatedObjects[$objclass][$k]= & $obj;
                    $added= true;
                }
                elseif ($k === $key) {
                    $this->set($k, $fkval);
                    $this->_relatedObjects[$objclass][$k]= & $obj;
                    $added= true;
                }
                if ($added) {
                    $this->_dirty[$k]= $k;
                }
            } else {
                $this->xpdo->_log(XPDO_LOG_LEVEL_WARN, "Foreign key definition for class {$objclass}, key {$k} not found.");
            }
        } else {
            $this->xpdo->_log(XPDO_LOG_LEVEL_WARN, "Attempt to add an object to a field ({$k}) that is not defined as a foreign key");
        }
        if (!$added) {
            $this->xpdo->_log(XPDO_LOG_LEVEL_WARN, "Could not add related object!");
        }
        return $added;
    }

    /**
     * Adds an object or collection of objects related to this class.
     * 
     * This method adds an object or collection of objects in a one-to-
     * many foreign key relationship with this object to the internal list of
     * related objects.  By adding these related objects, you can cascade
     * {@link save()}, {@link remove()}, and other operations based on the type
     * of relationships defined.
     * 
     * @see addOne()
     * @see getOne()
     * @see getMany()
     * 
     * @param mixed $obj A single object or collection of objects to be related
     * to this instance via the intersection class.
     */
    function addMany(& $obj, $fk= '') {
        $added= false;
        if (!is_array($obj)) {
            if (is_object($obj) && $objclass= get_class($obj)) {
                if ($fkMeta= $this->getFKDefinition($objclass, $fk)) {
                    $key= $fkMeta['cardinality'] === 'many' ? $fkMeta['key'] : '';
                    if ($key) {
                        $objpk= $obj->getPrimaryKey();
                        if (is_array($objpk)) {
                            $objpk= implode('-', $objpk);
                        }
                        if (!$objpk) {
                            $objpk= '__new' . isset ($this->_relatedObjects[$objclass][$key]) ? count($this->_relatedObjects[$objclass][$key]) : 0;
                        }
                        $this->_relatedObjects[$objclass][$key][$objpk]= & $obj;
                        if ($this->xpdo->getDebug() === true) $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, 'Added related object with key: ' . $key . ' and pk: ' . $objpk . "\n" . print_r($obj->_fields, true));
                        $added= true;
                    }
                }
            }
        } else {
            foreach ($obj as $o) {
                $added= $this->addMany($o, $fk);
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
     * seconds before expiring.  Overrides the cacheFlag for the object.
     *  
     * @return boolean Returns true on success, false on failure.
     */
    function save($cacheFlag= null) {
        $result= true;
        $sql= '';
        $updateSql= array ();
        $pkGenerated= false;
        $pk= $this->getPrimaryKey();
        if (!$pk) {
            $this->_dirty= array_combine(array_keys($this->_fields), array_keys($this->_fields));
        }
        if (!empty ($this->_relatedObjects)) {
            if (!empty ($this->_composites)) {
                foreach ($this->_composites as $compositeClass => $composites) {
                    foreach ($composites as $compositeKey => $composite) {
                        if (isset ($this->_relatedObjects[$compositeClass][$compositeKey])) {
                            if ($compositeKey === $composite['local'] && !$this->get($compositeKey) && $compositeKey !== $this->getPK()) {
                                if (is_array($this->_relatedObjects[$compositeClass][$compositeKey])) {
                                    foreach ($this->_relatedObjects[$compositeClass][$compositeKey] as $roKey => $ro) {
                                        $this->_saveRelatedObject($ro, $composite['local'], $composite['foreign']);
                                    }
                                }
                                elseif ($relatedObj= & $this->_relatedObjects[$compositeClass][$compositeKey]) {
                                    $this->_saveRelatedObject($relatedObj, $composite['local'], $composite['foreign']);
                                }
                            }
                        }
                    }
                }
            }
            if (!empty ($this->_aggregates)) {
                foreach ($this->_aggregates as $aggregateClass => $aggregates) {
                    foreach ($aggregates as $aggregateKey => $aggregate) {
                        if (isset ($this->_relatedObjects[$aggregateClass][$aggregateKey])) {
                            if ($aggregateKey === $aggregate['local'] && !$this->get($aggregateKey) && $aggregateKey !== $this->getPK()) {
                                if (is_array($this->_relatedObjects[$aggregateClass][$aggregateKey])) {
                                    foreach ($this->_relatedObjects[$aggregateClass][$aggregateKey] as $roKey => $ro) {
                                        $this->_saveRelatedObject($ro, $composite['local'], $aggregate['foreign']);
                                    }
                                }
                                if ($relatedObj= & $this->_relatedObjects[$aggregateClass][$aggregateKey]) {
                                    $this->_saveRelatedObject($relatedObj, $composite['local'], $aggregate['foreign']);
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty ($this->_dirty)) {
            $cols= array ();
            $bindings= array ();
            $updateSql= array ();
            foreach (array_keys($this->_dirty) as $_k) {
                if (!isset ($this->_fieldMeta[$_k])) {
                    continue;
                }
                $skip= false;
                if (isset ($this->_fieldMeta[$_k]['index']) && $this->_fieldMeta[$_k]['index'] === 'pk' && isset ($this->_fieldMeta[$_k]['generated']) && $this->_fieldMeta[$_k]['generated'] == 'native' && $this->getPKType() !== 'compound') {
                    $pkGenerated= true;
                    $skip= true;
                }
                if ($skip) {
                    continue;
                }
                if ($this->_fieldMeta[$_k]['phptype'] === 'password') {
                    $this->_fields[$_k]= md5($this->_fields[$_k]);
                }
                $fieldType= PDO_PARAM_STR;
                $fieldValue= $this->_fields[$_k];
                if ($fieldValue === null || $fieldValue === 'NULL') {
                    $fieldType= PDO_PARAM_NULL;
                    $fieldValue= 'NULL';
                }
                elseif (in_array($this->_fieldMeta[$_k]['phptype'], array ('timestamp', 'datetime')) && in_array($fieldValue, $this->_currentTimestamps)) {
                    if ($this->_new) {
                        $this->_fields[$_k]= $this->strftime('%Y-%m-%d %H:%M:%S');
                        continue;
                    }
                    $fieldType= PDO_PARAM_NULL;
                }
                elseif (in_array($this->_fieldMeta[$_k]['phptype'], array ('date')) && in_array($fieldValue, $this->_currentDates)) {
                    if ($this->_new) {
                        $this->_fields[$_k]= $this->strftime('%Y-%m-%d'); 
                        continue;
                    }
                    $fieldType= PDO_PARAM_NULL;
                }
                elseif (!in_array($this->_fieldMeta[$_k]['phptype'], array ('string','password','datetime','timestamp','date','time'))) {
                    $fieldType= PDO_PARAM_INT;
                }
                if (!$pk || $this->_new) {
                    $cols[$_k]= "`{$_k}`";
                    $bindings[":{$_k}"]['value']= $fieldValue;
                    $bindings[":{$_k}"]['type']= $fieldType;
                } else {
                    $bindings[":{$_k}"]['value']= $fieldValue;
                    $bindings[":{$_k}"]['type']= $fieldType;
                    $updateSql[]= "`{$_k}` = :{$_k}";
                }
            }
            if ($this->_new) {
                $sql= "INSERT INTO {$this->_table} (" . implode(', ', array_values($cols)) . ") VALUES(" . implode(', ', array_keys($bindings)) . ")";
            } else {
                if ($pk && $pkn= $this->getPK()) {
                    if (is_array($pkn)) {
                        $iteration= 0;
                        $where= '';
                        foreach ($pkn as $k => $v) {
                            $vt= PDO_PARAM_INT;
                            if ($this->_fieldMeta[$k]['phptype'] == 'string') {
                                $vt= PDO_PARAM_STR;
                            }
                            if ($iteration) {
                                $where .= " AND ";
                            }
                            $where .= "`{$k}` = :{$k}";
                            $bindings[":{$k}"]['value']= $this->_fields[$k];
                            $bindings[":{$k}"]['type']= $vt;
                            $iteration++;
                        }
                    } else {
                        $pkn= $this->getPK();
                        $pkt= PDO_PARAM_INT;
                        if ($this->_fieldMeta[$pkn]['phptype'] == 'string') {
                            $pkt= PDO_PARAM_STR;
                        }
                        $bindings[":{$pkn}"]['value']= $pk;
                        $bindings[":{$pkn}"]['type']= $pkt;
                        $where= '`' . $pkn . '` = :' . $pkn;
                    }
                    if (!empty ($updateSql)) {
                        $sql= "UPDATE {$this->_table} SET " . implode(',', $updateSql) . " WHERE {$where} LIMIT 1";
                    }
                }
            }
            if (!empty ($sql) && $criteria= new xPDOCriteria($this->xpdo, $sql)) {
                if (!empty ($bindings)) {
                    $criteria->bind($bindings, true, false);
                }
                if ($this->xpdo->getDebug() === true) $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Executing SQL:\n{$sql}\nwith bindings:\n" . print_r($bindings, true));
                if (!$result= $criteria->stmt->execute()) {
                    $errorInfo= $criteria->stmt->errorInfo();
                    $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Error " . $criteria->stmt->errorCode() . " executing statement:\n{$sql}\n" . print_r($errorInfo, true));
                    if ($errorInfo[1] == '1146') {
                        if ($this->xpdo->getManager() && $this->xpdo->manager->createObjectContainer(get_class($this)) === true) {
                            if (!$result= $criteria->stmt->execute()) {
                                $this->xpdo->_log(XPDO_LOG_LEVEL_FATAL, "Error " . $criteria->stmt->errorCode() . " executing statement:\n{$sql}\n" . print_r($criteria->stmt, true));
                            }
                        } else {
                            $this->xpdo->_log(XPDO_LOG_LEVEL_FATAL, "Error " . $this->xpdo->errorCode() . " attempting to create object container for class {$className}:\n" . print_r($this->xpdo->errorInfo(), true));
                        }
                    }
                }
                if ($result) {
                    if ($this->getPK() && !$pk) {
                        if ($pkGenerated) {
                            $this->_fields[$this->getPK()]= $this->xpdo->lastInsertId();
                        }
                        $pk= $this->getPrimaryKey();
                    }
					if ($pk || !$this->getPK()) {
                        $this->_dirty= array();
                        $this->_new= false;
	                }
                    if ($this->xpdo->_cacheEnabled && $pk && ($cacheFlag || ($cacheFlag === null && $this->_cacheFlag))) {
                        $cacheKey= is_array($pk) ? implode('_', $pk) : $pk;
                        if (is_bool($cacheFlag)) {
                            $expires= 0;
                        } else {
                            $expires= intval($cacheFlag);
                        }                  
                        $this->xpdo->toCache(get_class($this) . '_' . $cacheKey, $this, $expires);
                    }
                }
            }
        }
        if (!$this->_new && !empty ($this->_relatedObjects)) {
            foreach ($this->_aggregates as $aggregateClass => $aggregates) {
                foreach ($aggregates as $aggregateKey => $aggregate) {
                    if (isset ($this->_relatedObjects[$aggregateClass][$aggregateKey])) {
                        if ($relatedObjs= $this->_relatedObjects[$aggregateClass][$aggregateKey]) {
                            if (!is_array($relatedObjs)) {
                                $relatedObjs= array (
                                    $relatedObjs
                                );
                            }
                            foreach ($relatedObjs as $relatedObj) {
                                if (!empty ($relatedObj->_dirty)) {
                                    if (!$relatedObj->get($aggregate['foreign'])) {
                                        $relatedObj->set($aggregate['foreign'], $this->get($aggregate['local']));
                                    }
                                    $result= $relatedObj->save();
                                }
                            }
                        }
                    }
                }
            }
            foreach ($this->_composites as $compositeClass => $composites) {
                foreach ($composites as $compositeKey => $composite) {
                    if (isset ($this->_relatedObjects[$compositeClass][$compositeKey])) {
                        if ($relatedObjs= $this->_relatedObjects[$compositeClass][$compositeKey]) {
                            if (!is_array($relatedObjs)) {
                                $relatedObjs= array (
                                    $relatedObjs
                                );
                            }
                            foreach ($relatedObjs as $relatedObj) {
                                if (!empty ($relatedObj->_dirty)) {
                                    if (!$relatedObj->get($composite['foreign'])) {
                                        $relatedObj->set($composite['foreign'], $this->get($composite['local']));
                                    }
                                    $result= $relatedObj->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($result) {
            $this->_dirty= array ();
        }
        return $result;
    }

    function _saveRelatedObject(& $obj, $local, $foreign) {
        $saved= false;
        if (!$this->_new) {
            if ($fk= $this->get($local)) {
                $obj->set($foreign, $fk);
            }
        }
        if (!empty ($obj->_dirty) || $obj->_new) {
            $saved= $obj->save();
        }
        if (!$obj->_new) {
            if ($fk= $obj->get($foreign)) {
                $this->set($local, $fk);
            }
        }
        if ($this->xpdo->getDebug() === true) $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, ($saved ? 'Successfully saved' : 'Could not save') . " related object\nMain object: " . print_r($this->toArray(), true) . "\nRelated Object: " . print_r($obj->toArray(), true));
        return $saved;
    }

    /**
     * Remove the persistent instance of an object permanently.
     * 
     * Deletes the persistent object isntance stored in the database when
     * called, including any dependent objects defined by composite foreign key
     * relationships.
     * 
     * {@internal @todo Implement some way to reassign ownership of related
     * composite objects when remove is called, perhaps by passing an another
     * object instance as an optional parameter.}}
     * 
     * @return boolean Returns true on success, false on failure.
     */
    function remove() {
        $result= false;
        if ($pk= $this->getPrimaryKey()) {
            if (!empty ($this->_composites)) {
                $collection= array ();
                foreach ($this->_composites as $compositeClass => $composites) {
                    foreach ($composites as $composite) {
                        if ($composite['cardinality'] === 'many') {
                            if ($many= $this->getMany($compositeClass, $composite['foreign'])) {
                                $collection= array_merge($collection, $many);
                            }
                        }
                        elseif ($one= $this->getOne($compositeClass, $composite['key'])) {
                            $collection= array_merge($collection, array (
                                $one
                            ));
                        }
                    }
                }
                foreach ($collection as $dependent) {
                    if (!$dependent->remove()) {
                        $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Error removing dependent object: " . print_r($dependent->toArray(), true));
                    }
                }
            }
            $bindings= array ();
            $stmt= null;
            $sql= "DELETE FROM {$this->_table} WHERE ";
            if (is_array($pk)) {
                $iteration= 0;
                foreach ($pk as $k => $v) {
                    $pkType= $this->_fieldMeta[$k]['phptype'];
                    if ($iteration) {
                        $sql .= " AND ";
                    }
                    $sql .= "{$k} = :{$k}";
                    $bindings[":{$k}"]= array (
                        'value' => $v,
                        'type' => $pkType === 'string' ? PDO_PARAM_STR : PDO_PARAM_INT,
                        'length' => 0
                    );
                    $iteration++;
                }
                $sql .= " LIMIT 1";
                $iteration= 0;
            } else {
                $pkName= $this->getPK();
                $pkType= $this->_fieldMeta[$pkName]['phptype'];
                $sql .= "`{$pkName}` = :{$pkName} LIMIT 1";
                $bindings[":{$pkName}"]= array (
                    'value' => $pk,
                    'type' => $pkType === 'string' ? PDO_PARAM_STR : PDO_PARAM_INT,
                    'length' => 0
                );
            }
            if (!empty ($bindings)) {
                $criteria= new xPDOCriteria($this->xpdo, $sql, $bindings, false);
                if (!$result= $criteria->stmt->execute()) {
                    $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'Could not delete from ' . $this->_table . '; primary key specified was ' . print_r($this->get($this->getPK())) . "\n" . print_r($criteria->stmt->errorInfo(), true));
                } elseif ($this->xpdo->_cacheEnabled) {
                    $cacheKey= is_array($pk) ? implode('_', $pk) : $pk;
                    $this->toCache(get_class($this) . '_' . $cacheKey, null);
                }
            } else {
                $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'Could not build criteria to delete from ' . $this->_table . '; primary key specified was ' . print_r($this->get($this->getPK())));
            }
        }
        return $result;
    }

    /**
     * Gets the value (or values) of the primary key field(s) for the object.
     * 
     * @return mixed The string (or an array) representing the value(s) of the
     * primary key field(s) for this instance.
     */
    function getPrimaryKey() {
        $value= null;
        if ($pk= $this->getPK()) {
            if (is_array($pk)) {
                foreach ($pk as $k) {
                    $_pk= $this->get($k);
                    if ($_pk) {
                        $value[$k]= $_pk;
                    } else {
                        $value= null;
                        break;
                    }
                }
            } else {
                $value= $this->get($pk);
            }
        }
        if (!$value && $this->xpdo->getDebug() === true) {
            $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "No primary key value found for pk definition: " . print_r($this->getPK(), true));
        }
        return $value;
    }

    /**
     * Gets the name (or names) of the primary key field(s) for the object.
     * 
     * @return mixed The string (or an array of strings) representing the name(s)
     * of the primary key field(s) for this instance.
     */
    function getPK() {
        if ($this->_pk === null) {
            $this->_pk= $this->xpdo->getPK(get_class($this));
        }
        return $this->_pk;
    }

    /**
     * Gets the type of the primary key field for the object.
     * 
     * @return string The type of the primary key field for this instance.
     */
    function getPKType() {
        if ($this->_pktype === null) {
            if ($this->_pk === null) {
                $this->getPK();
            }
            $this->_pktype= $this->xpdo->getPKType(get_class($this));
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
    function getFKClass($k) {
        $fkclass= null;
        if (is_string($k)) {
            if (!empty ($this->_aggregates)) {
                foreach ($this->_aggregates as $aggregateClass => $aggregate) {
                    foreach ($aggregate as $key => $aggregateDef) {
                        if ($aggregateDef['local'] === $k) {
                            $fkclass= $aggregateClass;
                            break 2;
                        }
                    }
                }
            }
            if ($fkclass && !empty ($this->_composites)) {
                foreach ($this->_composites as $compositeClass => $composite) {
                    foreach ($composite as $key => $compositeDef) {
                        if ($compositeDef['local'] === $k) {
                            $fkclass= $compositeClass;
                            break 2;
                        }
                    }
                }
            }
        }
        if ($this->xpdo->getDebug() === true) $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Returning foreign key class {$fkclass} for column {$k}");
        return $fkclass;
    }

    /**
     * Get a foreign key definition for a specific classname.
     * 
     * This is generally used to lookup classes in a one-to-many relationship
     * with the current object.
     * 
     * @param string $class Name of the class to lookup a foreign key definition
     * from.
     * @param string $key The field key defining the relationship with the
     * class; there will be cases with multiple foreign keys exist to the same
     * class.
     * @return array A foreign key definition.
     */
    function getFKDefinition($class, $key= '') {
        $def= null;
        if ($class= $this->xpdo->loadClass($class)) {
            if (isset ($this->_aggregates[$class])) {
                if (empty ($key)) {
                    reset($this->_aggregates[$class]);
                    $key= key($this->_aggregates[$class]);
                }
                if (isset ($this->_aggregates[$class][$key])) {
                    $def= $this->_aggregates[$class][$key];
                    $def['key']= $key;
                    $def['type']= 'aggregate';
                    $def['class']= $class;
                }
            }
            if (isset ($this->_composites[$class])) {
                if (empty ($key)) {
                    reset($this->_composites[$class]);
                    $key= key($this->_composites[$class]);
                }
                if (isset ($this->_composites[$class][$key])) {
                    $def= $this->_composites[$class][$key];
                    $def['key']= $key;
                    $def['type']= 'composite';
                    $def['class']= $class;
                }
            }
        }
        if ($def === null) {
            $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'No foreign key definition for class: ' . $class . ' (key specified: ' . $key . ')');
        }
        return $def;
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
    function getFieldName($k, $alias= null) {
        if ($this->fieldNames === null) {
            $this->_initFields();
        }
        $name= null;
        if (is_string($k) && isset ($this->fieldNames[$k])) {
            $name= $this->fieldNames[$k];
        }
        if ($name !== null && $alias !== null) {
            $name= str_replace("{$this->_table}.", "{$alias}.", $name);
        }
        return $name;
    }

    /**
     * Copies the object fields and corresponding values to an associative array.
     * 
     * @return array An array representation of the object fields/values.
     */
    function toArray($keyPrefix= '', $rawValues= false) {
        $objarray= null;
        if (is_array($this->_fields)) {
            $keys= array_keys($this->_fields);
            foreach ($keys as $key) {
                $objarray[$keyPrefix . $key]= $rawValues ? $this->_fields[$key] : $this->get($key);
            }
        }
        return $objarray;
    }

    /**
     * Sets object fields from an associative array of key => value pairs.
     * 
     * @param array fldarray An associative array of key => values.
     */
    function fromArray($fldarray, $keyPrefix= '', $setPrimaryKeys= false, $rawValues= false) {
        if (is_array($fldarray)) {
            $pkSet= false;
            $generatedKey= false;
            while (list ($key, $val)= each($fldarray)) {
                if (!empty ($keyPrefix)) {
                    $prefixPos= strpos($key, $keyPrefix);
                    if ($prefixPos === 0) {
                        $key= substr($key, strlen($keyPrefix));
                    } else {
                        continue;
                    }
                    if ($this->xpdo->getDebug() === true) $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Stripped prefix {$keyPrefix} to produce key {$key}");
                }
                if (isset ($this->_fieldMeta[$key]['index']) && $this->_fieldMeta[$key]['index'] == 'pk') {
                    if ($setPrimaryKeys) {
                        if (isset ($this->_fieldMeta[$key]['generated'])) {
                            $generatedKey= true;
                        }
                        if ($this->_new) {
                            if ($rawValues || $generatedKey) {
                                $this->_fields[$key]= $val;
                                $this->_dirty[$key]= $key;
                            } else {
                                $this->set($key, $val);
                            }
                            $pkSet= true;
                        }
                    } else {
                        continue;
                    }
                }
                elseif (isset ($this->_fieldMeta[$key])) {
                    if ($rawValues) {
                        $this->_fields[$key]= $val;
                        $this->_dirty[$key]= $key;
                    } else {
                        $this->set($key, $val);
                    }
                }
            }
        }
    }

    /**
     * Gets related objects by a foreign key and specified criteria.
     * 
     * @access protected
     * @return array|object A collection of objects, or single object matching
     * the criteria.
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     */
    function & _getRelatedObjectsByFK($class, $key, $criteria= null, $cacheFlag= false) {
        $collection= null;
        if ($class= $this->xpdo->loadClass($class)) {
            if (isset ($this->_relatedObjects[$class][$key])) {
                $collection= $this->_relatedObjects[$class][$key];
            } else {
                if ($criteria === null) {
                    $fktable= $this->xpdo->getTableName($class);
                    $fkMeta= $this->getFKDefinition($class, $key);
                    $sql= "SELECT * FROM {$fktable} WHERE `{$fkMeta['foreign']}` = :{$fkMeta['foreign']}";
                    $fkvalue= $this->get($fkMeta['local']);
                    $criteria= new xPDOCriteria($this->xpdo, $sql);
                    $criteria->bind(array (":{$fkMeta['foreign']}" => $fkvalue), true, $cacheFlag);
                }
                if ($collection= $this->xpdo->getCollection($class, $criteria)) {
                    foreach ($collection as $pk => $obj) {
                        $this->_relatedObjects[$class][$key][$pk]= $obj;
                    }
                }
            }
        }
        if ($this->xpdo->getDebug() === true) $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "_getRelatedObjectsByFK :: {$class}.{$key} :: " . is_object($criteria) ? print_r($criteria->stmt, true) : 'no criteria');
        return $collection;
    }

    /**
     * Initializes the field names with the qualified table name.
     * 
     * Once this is called, you can lookup the qualified name by the field name
     * itself.
     * 
     * @access protected
     */
    function _initFields() {
        reset($this->_fields);
        while (list ($k, $v)= each($this->_fields)) {
            $this->fieldNames[$k]= "`{$this->_table}`.`{$k}`";
        }
    }
    
    function date($format= '', $timestamp= false, $isGMT= false) {
        if (!function_exists('adodb_date')) {
            include_once(XPDO_CORE_PATH . 'datetime/adodb-time.inc.php');
        }
        if ($timestamp === false) {
            $timestamp= time();
        }
        return adodb_date($format, $timestamp, $isGMT);
    }

    function date2($format= '', $datestring= false, $isGMT= false) {
        if (!function_exists('adodb_date2')) {
            include_once(XPDO_CORE_PATH . 'datetime/adodb-time.inc.php');
        }
        return adodb_date2($format, $datestring, $isGMT);
    }

    function strtotime($strInput) {
        $iVal= -1;
        for ($i= 1900; $i <= 1969; $i++) {
            // Check for this year string in date
            $strYear= (string) $i;
            if (!(strpos($strInput, $strYear) === false)) {
                $replYear= $strYear;
                $yearSkew= 1970 - $i;
                $strInput= str_replace($strYear, '1970', $strInput);
            }
        }
        $iVal= strtotime($strInput);
        if ($yearSkew > 0) {
            $numSecs= (60 * 60 * 24 * 365 * $yearSkew);
            $iVal= $iVal - $numSecs;
            $numLeapYears= 0; // determine number of leap years in period
            for ($j= $replYear; $j <= 1969; $j++) {
                $thisYear= $j;
                $isLeapYear= false;
                // Is div by 4?
                if (($thisYear % 4) == 0) {
                    $isLeapYear= true;
                }
                // Is div by 100?
                if (($thisYear % 100) == 0) {
                    $isLeapYear= false;
                }
                // Is div by 1000?
                if (($thisYear % 1000) == 0) {
                    $isLeapYear= true;
                }
                if ($isLeapYear == true) {
                    $numLeapYears++;
                }
            }
            $iVal= $iVal - (60 * 60 * 24 * $numLeapYears);
        }
        return $iVal;
    }

    function strftime($format= "", $timestamp= false, $isGMT= false) {
        if (!function_exists('adodb_strftime')) {
            include_once(XPDO_CORE_PATH . 'datetime/adodb-time.inc.php');
        }
        if ($timestamp === false) {
            $timestamp= time();
        }
        return adodb_strftime($format, $timestamp, $isGMT);
    }

    function toJSON($keyPrefix= '', $rawValues= false) {
        $json= '';
        $array= $this->toArray($keyPrefix, $rawValues);
        if ($array) {
            $json= $this->xpdo->toJSON($array);
        }
        return $json;
    }
    
    function fromJSON($jsonSource, $keyPrefix= '', $setPrimaryKeys= false, $rawValues= false) {
        $array= $this->xpdo->fromJSON($jsonSource, true);
        if ($array) {
            $this->fromArray($array, $keyPrefix, $setPrimaryKeys, $rawValues);
        }
    }   
}

/**
 * Extend this abstract class to define a class having an integer primary key
 * field.
 * 
 * @package xpdo.om.mysql
 */
class xPDOSimpleObject extends xPDOObject {}
