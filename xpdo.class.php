<?php
/*
 * OpenExpedio (XPDO) is an ultra-light, PHP 4.3+ compatible ORB (Object-
 * Relational Bridge) library based around PDO (http://php.net/pdo/).  It uses
 * native PDO if available or provides a subset implementation for use with PHP
 * 4 on platforms that do not include the native PDO extensions.
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
 * This is the main file to include in your scripts to use XPDO.
 * 
 * It defines PHP 4 and 5-compatible constants along with the core classes used
 * throughout the framework. The constants should be available regardless if you
 * are using native PDO or the OpenExpedio PDO implementation for PHP 4.3+.
 * OpenExpedio recommends using these constants instead of the STATIC class
 * variables in PDO when PHP 4 portability is a concern for your application, as
 * these constants mimic the native PDO static vars, which are unusable in PHP
 * 4.
 * @package xpdo
 */
if (!function_exists('array_combine')) {
    /**
     * Emulates PHP5 array_combine function for PHP 4.
     * 
     * @see http:/php.net/function.array_combine
     */
    function array_combine($keys, $values) {
        $keys= array_values((array) $keys);
        $values= array_values((array) $values);
        $n= max(count($keys), count($values));
        $r= array ();
        for ($i= 0; $i < $n; $i++) {
            $r[$keys[$i]]= $values[$i];
        }
        return $r;
    }
}
if (!defined('XPDO_PHP4_MODE')) {
    if (version_compare(phpversion(), '5.0.0') < 0) {
        define('XPDO_PHP4_MODE', true);
    } else {
        define('XPDO_PHP4_MODE', false);
    }
}
if (!defined('XPDO_CORE_PATH')) {
    /**
     * @internal This global variable is only used to set the {@link
     * XPDO_CORE_PATH} value upon initial include of this file.  Not meant for
     * external use.
     * @var string
     * @access private
     */
    $xpdo_core_path= strtr(realpath(dirname(__FILE__)), '\\', '/') . '/';
    /**
     * @var string The full path to the XPDO root directory.
     * 
     * Use of this constant is recommended for use when building any path in
     * your XPDO code.
     * 
     * <strong>WARNING</strong>: DO NOT undefine XPDO_CORE_PATH at any point or
     * any additional attempts to include this file will fail as the code
     * tries to redefine the additional XPDO_ and PDO_ constants, causing a
     * fatal PHP parser error.
     * 
     * @access public
     */
    define('XPDO_CORE_PATH', $xpdo_core_path);

    /**#@+
     * @var integer
     * @access public
     */
    define('XPDO_MODE_NATIVE', 1);
    define('XPDO_MODE_EMULATED', 2);
    define('XPDO_LOG_LEVEL_FATAL', 0);
    define('XPDO_LOG_LEVEL_ERROR', 1);
    define('XPDO_LOG_LEVEL_WARN', 2);
    define('XPDO_LOG_LEVEL_INFO', 3);
    define('XPDO_LOG_LEVEL_DEBUG', 4);
    define('PDO_PARAM_BOOL', 5);
    define('PDO_PARAM_NULL', 0);
    define('PDO_PARAM_INT', 1);
    define('PDO_PARAM_STR', 2);
    define('PDO_PARAM_LOB', 3);
    define('PDO_PARAM_STMT', 4);
    define('PDO_PARAM_INPUT_OUTPUT', -2147483648);
    define('PDO_ATTR_AUTOCOMMIT', 0);
    define('PDO_ATTR_PREFETCH', 1);
    define('PDO_ATTR_TIMEOUT', 2);
    define('PDO_ATTR_ERRMODE', 3);
    define('PDO_ATTR_SERVER_VERSION', 4);
    define('PDO_ATTR_CLIENT_VERSION', 5);
    define('PDO_ATTR_SERVER_INFO', 6);
    define('PDO_ATTR_CONNECTION_STATUS', 7);
    define('PDO_ATTR_CASE', 8);
    define('PDO_ATTR_CURSOR_NAME', 9);
    define('PDO_ATTR_CURSOR', 10);
    define('PDO_ATTR_ORACLE_NULLS', 11);
    define('PDO_ATTR_PERSISTENT', 12);
    define('PDO_ATTR_STATEMENT_CLASS', 13);
    define('PDO_ATTR_FETCH_TABLE_NAMES', 14);
    define('PDO_ATTR_FETCH_CATALOG_NAMES', 15);
    define('PDO_ATTR_DRIVER_NAME', 16);
    define('PDO_ATTR_STRINGIFY_FETCHES', 17);
    define('PDO_ATTR_MAX_COLUMN_LEN', 18);
    define('PDO_ATTR_EMULATE_PREPARES', 19);
    define('PDO_FETCH_LAZY', 1);
    define('PDO_FETCH_ASSOC', 2);
    define('PDO_FETCH_NUM', 3);
    define('PDO_FETCH_BOTH', 4);
    define('PDO_FETCH_OBJ', 5);
    define('PDO_FETCH_BOUND', 6);
    define('PDO_FETCH_COLUMN', 7);
    define('PDO_FETCH_CLASS', 8);
    define('PDO_FETCH_INTO', 9);
    define('PDO_FETCH_FUNC', 10);
    define('PDO_FETCH_GROUP', 65536);
    define('PDO_FETCH_UNIQUE', 196608);
    define('PDO_FETCH_CLASSTYPE', 262144);
    define('PDO_FETCH_SERIALIZE', 524288);
    define('PDO_FETCH_NAMED', 11);
    define('PDO_ERRMODE_SILENT', 0);
    define('PDO_ERRMODE_WARNING', 1);
    define('PDO_ERRMODE_EXCEPTION', 2);
    define('PDO_CASE_NATURAL', 0);
    define('PDO_CASE_LOWER', 2);
    define('PDO_CASE_UPPER', 1);
    define('PDO_NULL_NATURAL', 0);
    define('PDO_NULL_EMPTY_STRING', 1);
    define('PDO_NULL_TO_STRING', 2);
    define('PDO_ERR_NONE', '00000');
    define('PDO_FETCH_ORI_NEXT', 0);
    define('PDO_FETCH_ORI_PRIOR', 1);
    define('PDO_FETCH_ORI_FIRST', 2);
    define('PDO_FETCH_ORI_LAST', 3);
    define('PDO_FETCH_ORI_ABS', 4);
    define('PDO_FETCH_ORI_REL', 5);
    define('PDO_CURSOR_FWDONLY', 0);
    define('PDO_CURSOR_SCROLL', 1);
    define('PDO_MYSQL_ATTR_USE_BUFFERED_QUERY', 1000);
    define('PDO_MYSQL_ATTR_LOCAL_INFILE', 1001);
    define('PDO_MYSQL_ATTR_INIT_COMMAND', 1002);
    define('PDO_MYSQL_ATTR_READ_DEFAULT_FILE', 1003);
    define('PDO_MYSQL_ATTR_READ_DEFAULT_GROUP', 1004);
    define('PDO_MYSQL_ATTR_MAX_BUFFER_SIZE', 1005);
    define('PDO_MYSQL_ATTR_DIRECT_QUERY', 1006);
    define('PDO_PGSQL_ATTR_DISABLE_NATIVE_PREPARED_STATEMENT', 1000);
    /**#@-*/
}

if (!class_exists('PDO')) {
    if (!defined('XPDO_MODE')) {
        define('XPDO_MODE', XPDO_MODE_EMULATED);
    }
} else {
    if (!defined('XPDO_MODE')) {
        define('XPDO_MODE', XPDO_MODE_NATIVE);
    }
}

/**
 * A wrapper for PDO that powers an object-relational data model.
 * 
 * xPDO provides centralized data access via a simple object-oriented API, to
 * and defined data structure. It provides the de facto methods for connecting
 * to a data source, getting persistence metadata for any class extended from
 * the {@link xPDOObject} class (core or custom), loading data source managers
 * when needed to manage table structures, and retrieving instances (or rows) of
 * any object in the model.
 * 
 * Through various extensions, you can also reverse and forward engineer classes
 * and metadata maps for XPDO, have classes, models, and properties maintain
 * their own containers (databases, tables, columns, etc.) or changes to them,
 * and much more.
 * 
 * @package xpdo
 */
class xPDO {
    var $pdo= null;
    /**
     * A array of xPDO configuration attributes.
     * @var array
     * @access public
     */
    var $config= null;
    /**
     * A map of data source meta data for all loaded classes.
     * @var array
     * @access public
     */
    var $map= array ();
    /**
     * A default package for specifying classes by name.
     * @var string
     * @access public
     */
    var $package= '';
    /**
     * {@link xPDOManager} instance, loaded only if needed to manage datasource
     * containers, data structures, etc.
     * @var xPDOManager
     * @access public
     */
    var $manager= null;
    /**
     * @var xPDOCacheManager The cache service provider registered for this xPDO
     * instance.
     */
    var $cacheManager= null;
    /**
     * @var float Start time of the request, initialized when the constructor is
     * called.
     */
    var $startTime= 0;
    /**
     * @var int The number of direct DB queries executed during a request.
     */
    var $executedQueries= 0;
    /**
     * @var int The amount of request handling time spent with DB queries.
     */
    var $queryTime= 0;

    /**
     * @var integer The logging level for the XPDO instance.
     */
    var $logLevel= XPDO_LOG_LEVEL_FATAL;

    /**
     * @var string The default logging target for the XPDO instance.
     */
    var $logTarget= 'ECHO';

    var $_debug= false;
    var $_nativeMode= true;
    var $_cacheEnabled= false;

    /**
     * The xPDO Constructor.
     * 
     * This method is used to create a new xPDO object with a connection to a
     * specific database container.
     * 
     * @param mixed $dsn A valid DSN connection string.
     * @param string $username The database username with proper permissions.
     * @param string $password The password for the database user.
     * @param string $tablePrefix A prefix applied to all database container
     * names, to isolate multiple installations or conflicting table names that
     * might need to coexist in a single database container.
     * @param mixed $driverOptions Driver-specific PDO options.
     */
    function xPDO($dsn, $username= '', $password= '', $tablePrefix= '', $driverOptions= null) {
        $this->__construct($dsn, $username, $password, $tablePrefix, $driverOptions);
    }
    function __construct($dsn, $username= '', $password= '', $tablePrefix= '', $driverOptions= null) {
        $this->_nativeMode= (XPDO_MODE === XPDO_MODE_NATIVE);
        $this->config= $this->parseDSN($dsn);
        $this->config['dsn']= $dsn;
        $this->config['username']= $username;
        $this->config['password']= $password;
        $this->config['table_prefix']= $tablePrefix;
        $this->config['driverOptions']= $driverOptions;
        $this->loadClass('xPDOObject');
        $this->loadClass('xPDOSimpleObject');
    }
    
    function connect($driverOptions= array ()) {
        if ($this->pdo === null) {
            if (class_exists('PDO') || include_once (XPDO_CORE_PATH . 'pdo.class.php')) {
                if (!empty ($driverOptions)) {
                    $this->config['driverOptions']= array_merge($this->config['driverOptions'], $driverOptions);
                }
                $this->pdo= new PDO($this->config['dsn'], $this->config['username'], $this->config['password'], $this->config['driverOptions']);
                if ($this->config['dbtype'] === null) {
                    $this->config['dbtype']= $this->getAttribute(PDO_ATTR_DRIVER_NAME);
                }
            }
        }
        return $this->pdo;
    }
    
    /**
     * Sets the default package name to use when looking up classes.
     * 
     * This package is of the form package.subpackage.subsubpackage and will be
     * added to the beginning of every XPDO class that is referenced in XPDO
     * methods such as {@link loadClass()}, {@link getObject()},
     * {@link getCollection()}, {@link getOne()}, {@link addOne()}, etc.
     * 
     * @param string $pkg A package name to use when looking up classes in XPDO.
     */
    function setPackage($pkg= '') {
        $this->package= $pkg;
    }
    
    /**
     * Load a class by fully qualified name.
     * 
     * The $fqn should in the format:
     * 
     *    dir_a.dir_b.dir_c.classname
     * 
     * which will translate to:
     * 
     *    XPDO_CORE_PATH/om/dir_a/dir_b/dir_c/dbtype/classname.class.php
     * 
     * @param string $fqn The fully-qualified name of the class to load.
     * @return string|boolean The actual classname if successful, or false if
     * not.
     */
    function loadClass($fqn, $path= '', $ignorePkg= false, $transient= false) {
        if (empty($fqn)) {
            $this->_log(XPDO_LOG_LEVEL_ERROR, "No class specified for loadClass");
			return false;
        }
        $class= false;
        if (empty ($path)) {
            $path= XPDO_CORE_PATH . 'om/';
        }
        if (!$ignorePkg && !empty ($this->package)) {
            $fqn= $this->package . '.' . $fqn;
        }
        // extract classname
        if (($pos= strrpos($fqn, '.')) === false) {
            $class= $fqn;
            if ($transient) {
                $fqn= strtolower($class);
            } else {
                $fqn= $this->config['dbtype'] . '.' . strtolower($class);
            }
        } else {
            if ($transient) {
                $class= substr($fqn, $pos +1);
                $fqn= substr($fqn, 0, $pos) . '.' . strtolower($class);
            } else {
                $class= substr($fqn, $pos +1);
                $fqn= substr($fqn, 0, $pos) . '.' . $this->config['dbtype'] . '.' . strtolower($class);
            }
        }
        // check if class exists
        if (XPDO_PHP4_MODE) {
            $included= class_exists($class);
        } else {
            $included= class_exists($class, false);
        }
        if ($included) {
            if ($transient || (!$transient && isset ($this->map[$class]))) {
                return $class;
            }
        }
        if (!$included) {
            // turn to filesystem path and enforce all lower-case paths and filenames
            $fqcn= strtr($fqn, '.', '/') . '.class.php';
            // include class
            if (!$rt= include_once ($path . $fqcn)) {
                $this->_log(XPDO_LOG_LEVEL_ERROR, "Could not load class: {$class} from {$fqcn}");
                $class= false;
            }
        }
        if ($class && !$transient && !isset ($this->map[$class])) {
            $mapfile= strtr($fqn, '.', '/') . '.map.inc.php';
            $xpdo_meta_map= & $this->map;
            if (!$rt= include ($path . $mapfile)) {
                $this->_log(XPDO_LOG_LEVEL_WARN, "Could not load metadata map {$mapfile} for class {$class} from {$fqn}");
            }
        }
        return $class;
    }

    /**
     * Creates a new instance of a specified class.
     * 
     * All new objects created with this method are transient until saved for
     * the first time.
     * 
     * @param string $className Name of the class to get a new instance of.
     * @param array $fields An associated array of field names/values to
     * populate the object with.
     * @return object|null A new instance of the specified class, or null if a
     * new object could not be instantiated.
     */
    function newObject($className, $fields= array ()) {
        $instance= null;
        if ($this->loadClass($className)) {
            if ($instance= new $className ($this)) {
                if (is_array($fields) && !empty ($fields)) {
                    $instance->fromArray($fields);
                }
            }
        }
        return $instance;
    }

    /**
     * Retrieves a single object instance by the specified criteria.
     * 
     * The criteria can be a primary key value, and array of primary key values
     * (for multiple primary key objects) or an {@link xPDOCriteria} object. If
     * no $criteria parameter is specified, no class is found, or an object
     * cannot be located by the supplied criteria, null is returned.
     * 
     * @param string $className Name of the class to get an instance of.
     * @param mixed $criteria Primary key of the record or a xPDOCriteria object.
     * @param mixed $cacheFlag If an integer value is provided, this specifies
     * the time to live in the object cache; if cacheFlag === false, caching is
     * ignored for the object and if cacheFlag === true, the object will live in
     * cache indefinitely.
     * @return object|null An instance of the class, or null if it could not be
     * instantiated.
    */
    function getObject($className, $criteria= null, $cacheFlag= false) {
        $instance= null;
        if ($criteria !== null) {
            $instance= $this->load($className, $criteria, $cacheFlag);
        }
        return $instance;
    }

    /**
     * Retrieves a collection of xPDOObjects by the specified xPDOCriteria.
     * 
     * @param string $className Name of the class to search for instances of.
     * @param object|array|string $criteria A xPDOCriteria object with the
     * PDOStatement and bindings, an array of field key/value pairs to search
     * by, or a string indicating a predefined criteria to use.
     * @param mixed $cacheFlag If an integer value is provided, this specifies
     * the time to live in the result set cache; if cacheFlag === false, caching
     * is ignored for the collection and if cacheFlag === true, the objects will
     * live in cache indefinitely.
     * @return array|null An array of class instances retrieved.
    */
    function getCollection($className, $criteria= null, $cacheFlag= false) {
        $objCollection= null;
        $fromCache= false;
        $all= false;
        $rows= false;
        if ($className= $this->loadClass($className)) {
            if (is_array($criteria)) {
                $criteria= $this->getCriteria($className, $criteria);
            }
            if ($criteria === null) {
                $criteria= 'all';
            }
            if ($criteria === 'all') {
                $all= true;
                if ($this->_cacheEnabled && $cacheFlag) {
                    $rows= $this->fromCache($className . '_all');
                }
                if (!$rows) {
                    $table= $this->getTableName($className);
                    $criteria= new xPDOCriteria($this, "SELECT * FROM {$table}", array (), $cacheFlag);
                } else {
                    $fromCache= true;
                }
            }
            if (is_object($criteria->stmt) && !$rows) {
                $tstart= $this->getMicroTime();
                if ($criteria->stmt->execute()) {
                    $tend= $this->getMicroTime();
                    $totaltime= $tend - $tstart;
                    $this->queryTime= $this->queryTime + $totaltime;
                    $this->executedQueries= $this->executedQueries + 1;
                    $rows= $criteria->stmt->fetchAll(PDO_FETCH_ASSOC);
                }
            }
            if (is_array ($rows)) {
                foreach ($rows as $row) {
                    $instanceClass= $className;
                    if (isset ($row['class_key']) && $row['class_key'] && $this->loadClass($row['class_key'])) {
                        $instanceClass= $row['class_key'];
                    }
                    if ($obj= $this->newObject($instanceClass)) {
                        $obj->fromArray($row, '', true);
                        $obj->_new= false;
                        $obj->_dirty= array ();
                        if ($pkval= $obj->getPrimaryKey()) {
                            if (is_array($pkval)) {
                                $cacheKey= implode('_', $pkval);
                                $pkval= implode('-', $pkval);
                            } else {
                                $cacheKey= $pkval;
                            }
                            if ($this->_cacheEnabled && $cacheFlag) {
                                if (!$fromCache) {
                                    $this->toCache($instanceClass . '_' . $cacheKey, $obj, $cacheFlag);
                                } else {
                                    $obj->_cacheFlag= true;
                                }
                            }
                            $objCollection[$pkval]= $obj;
                        }
                    }
                }
            }
        }
        if ($this->_cacheEnabled && $cacheFlag && !$fromCache && $objCollection) {
            if ($all) {
                $this->toCache($className . '_all', $rows, $cacheFlag);
            } else {
                $this->toCache($criteria, $rows, $cacheFlag);
            }
        }
        return $objCollection;
    }

    /**
     * Gets criteria pre-defined in an {@link xPDOObject} class metadata definition.
     * 
     * @todo Define callback functions as an alternative to retreiving criteria
     * sql and/or bindings from the metadata.
     * @todo Move quotable operators to the db specific xPDOObject
     * implementation.
     * 
     * @param string $className The class to get predefined criteria for.
     * @param string $type The type of criteria to get (you can define any
     * type you want, but 'object' and 'collection' are the typical criteria
     * for retrieving single and multiple instances of an object).
     * @return xPDOCriteria A criteria object or null if not found.
     */
    function getCriteria($className, $type= 'collection', $cacheFlag= true) {
        $criteria= null;
        if ($className= $this->loadClass($className)) {
            $pk= $this->getPK($className);
            $pktype= $this->getPKType($className);
            if (is_array($type)) {
                if (!isset ($type[0])) {
                    $fieldMeta= $this->getFieldMeta($className);
                    $bindings= array ();
                    $fields= array ();
                    $quotableOperators= array (
                        '=',
                        '>',
                        '<',
                        '>=',
                        '<=',
                        '<>',
                        '!=',
                        'LIKE'
                    );
                    $quotableTypes= array (
                        'string',
                        'password',
                        'date',
                        'datetime',
                        'timestamp',
                        'time'
                    );
                    reset($type);
                    while (list ($key, $val)= each($type)) {
                        $operator= '=';
                        $key_prefix= '';
                        $key_operator= explode(':', $key);
                        if ($key_operator && count($key_operator) === 2) {
                            $key= $key_operator[0];
                            $operator= $key_operator[1];
                        }
                        elseif ($key_operator && count($key_operator) === 3) {
                            $key_prefix= $key_operator[0];
                            $key= $key_operator[1];
                            $operator= $key_operator[2];
                        }
                        if (array_key_exists($key, $fieldMeta)) {
                            $bindings[':' . $key]= $val;
                            $fields[$key]= $key_prefix ? $key_prefix . ' ' : '' . "`{$key}` " . $operator . ' ' . ":{$key}";
                        }
                    }
                    if (!empty ($fields) && !empty ($bindings)) {
                        $fieldList= implode(' AND ', $fields);
                        $tableName= $this->getTableName($className);
                        $sql= "SELECT * FROM {$tableName} WHERE {$fieldList}";
                        foreach ($bindings as $bk => $bv) {
                            if (!is_array($bv)) {
                                if (array_key_exists(substr($bk, 1), $fieldMeta)) {
                                    if (!in_array($fieldMeta[substr($bk, 1)]['phptype'], $quotableTypes) || $val == 'NULL') {
                                        $bt= PDO_PARAM_INT;
                                    } else {
                                        $bt= PDO_PARAM_STR;
                                    }
                                }
                                $bindings[$bk]= array (
                                    'value' => $bv,
                                    'type' => $bt,
                                    'length' => 0,
                                );
                            }
                        }
                        $criteria= new xPDOCriteria($this, $sql, $bindings, $cacheFlag);
                    }
                }
                elseif (isset ($type[0]) && is_array($pk) && count($type) == count($pk)) {
                    $bindings= array ();
                    $iteration= 0;
                    $tbl= $this->getTableName($className);
                    $sql= "SELECT * FROM {$tbl} WHERE ";
                    foreach ($pk as $k) {
                        if ($iteration) {
                            $sql .= " AND ";
                        }
                        if (!isset ($type[$iteration])) {
                            $type[$iteration]= null;
                        }
                        $sql .= "`{$k}` = :{$k}";
                        $bindings[":{$k}"]= $type[$iteration];
                        $iteration++;
                    }
                    if (!empty ($bindings)) {
                        $criteria= new xPDOCriteria($this, $sql, $bindings, $cacheFlag);
                    }
                }
            }
            elseif (($pktype == 'integer' && is_numeric($type)) || ($pktype == 'string' && is_string($type))) {
                if ($pktype == 'integer') {
                    $param_type= PDO_PARAM_INT;
                } else {
                    $param_type= PDO_PARAM_STR;
                }
                $tbl= $this->getTableName($className);
                $sql= "SELECT * FROM {$tbl} WHERE `{$pk}` = :{$pk}";
                $criteria= new xPDOCriteria($this, $sql);
                $criteria->bind(array (':' . $pk => array ('value' => $type, 'type' => $param_type, 'length' => 0)), true, $cacheFlag);
            }
        }
        if ($this->getDebug() === true) $this->_log(XPDO_LOG_LEVEL_DEBUG, 'Retrieving criteria object: ' . print_r($criteria, true));
        return $criteria;
    }

    /**
     * Gets the actual run-time table name from a specified class name.
     * 
     * @param string $className The name of the class to lookup a table name
     * for.
     * @param boolean $includeDb Qualify the table name with the database name.
     * @return string The table name for the class, or null if unsuccessful.
     */
    function getTableName($className, $includeDb= true) {
        $table= null;
        if ($className= $this->loadClass($className)) {
            if (isset ($this->map[$className]['table'])) {
                $table= $this->map[$className]['table'];
            }
            if (!$table && $ancestry= $this->getAncestry($className, false)) {
                foreach ($ancestry as $ancestor) {
                    if (isset ($this->map[$ancestor]['table']) && $table= $this->map[$ancestor]['table']) {
                        break;
                    }
                }
            }
        }
        if ($table) {
            $table= $this->_getFullTableName($table, $includeDb);
            if ($this->getDebug() === true) $this->_log(XPDO_LOG_LEVEL_DEBUG, 'Returning table name: ' . $table . ' for class: ' . $className);
        } else {
            $this->_log(XPDO_LOG_LEVEL_ERROR, 'Could not get table name for class: ' . $className);
        }
        return $table;
    }

    /**
     * Gets the actual run-time table type from a specified class name.
     * 
     * @param string $className The name of the class to lookup a table name
     * for.
     * @return string The table type for the class, or null if unsuccessful.
     */
    function getTableType($className) {
        $tableType= null;
        if ($className= $this->loadClass($className)) {
            if (isset ($this->map[$className]['tableType'])) {
                $tableType= $this->map[$className]['tableType'];
            }
            if (!$tableType && $ancestry= $this->getAncestry($className)) {
                foreach ($ancestry as $ancestor) {
                    if (isset ($this->map[$ancestor]['tableType'])) {
                        if ($tableType= $this->map[$ancestor]['tableType']) {
                            break;
                        }
                    }
                }
            }
        }
        return $tableType;
    }

    /**
     * Gets a list of fields (or columns) for an object by class name.
     * 
     * This includes default values for each field and is used by the objects
     * themselves to build their initial attributes based on class inheritence.
     * 
     * @param string $className The name of the class to lookup fields for.
     * @return array An array featuring field names as the array keys, and
     * default field values as the array values; empty array is returned if
     * unsuccessful.
     */
    function getFields($className) {
        $fields= array ();
        if ($className= $this->loadClass($className)) {
            if ($ancestry= $this->getAncestry($className)) {
                for ($i= count($ancestry) - 1; $i >= 0; $i--) {
                    if (isset ($this->map[$ancestry[$i]]['fields'])) {
                        $fields= array_merge($fields, $this->map[$ancestry[$i]]['fields']);
                    }
                }
            }
        }
        return $fields;
    }

    /**
     * Gets a list of field (or column) definitions for an object by class name.
     * 
     * These definitions are used by the objects themselves to build their
     * own meta data based on class inheritence.
     * 
     * @param string $className The name of the class to lookup fields meta data
     * for.
     * @return array An array featuring field names as the array keys, and
     * arrays of metadata information as the array values; empty array is
     * returned if unsuccessful.
     */
    function getFieldMeta($className) {
        $fieldMeta= array ();
        if ($className= $this->loadClass($className)) {
            if ($ancestry= $this->getAncestry($className)) {
                for ($i= count($ancestry) - 1; $i >= 0; $i--) {
                    if (isset ($this->map[$ancestry[$i]]['fieldMeta'])) {
                        $fieldMeta= array_merge($fieldMeta, $this->map[$ancestry[$i]]['fieldMeta']);
                    }
                }
            }
        }
        return $fieldMeta;
    }

    /**
     * Gets the primary key field(s) for a class.
     * 
     * @param string $className The name of the class to lookup the primary key
     * for.
     * @return mixed The name of the field representing a class instance primary
     * key, an array of key names for compound primary keys, or null if no
     * primary key is found or defined for the class.
     */
    function getPK($className) {
        $pk= null;
        if (strcasecmp($className, 'xPDOObject') == 0) {
            return $pk;
        }
        if ($actualClassName= $this->loadClass($className)) {
            if (isset ($this->map[$actualClassName]['fieldMeta'])) {
                foreach ($this->map[$actualClassName]['fieldMeta'] as $k => $v) {
                    if (isset ($v['index']) && isset ($v['phptype']) && $v['index'] == 'pk') {
                        $pk[$k]= $k;
                    }
                }
            }
            if ($ancestry= $this->getAncestry($actualClassName)) {
                foreach ($ancestry as $ancestor) {
                    if ($ancestorClassName= $this->loadClass($ancestor)) {
                        if (isset ($this->map[$ancestorClassName]['fieldMeta'])) {
                            foreach ($this->map[$ancestorClassName]['fieldMeta'] as $k => $v) {
                                if (isset ($v['index']) && isset ($v['phptype']) && $v['index'] == 'pk') {
                                    $pk[$k]= $k;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $this->_log(XPDO_LOG_LEVEL_ERROR, "Could not load class {$className}");
        }
        if ($pk && count($pk) === 1) {
            $pk= current($pk);
        }
        return $pk;
    }

    /**
     * Gets the type of primary key field for a class.
     * 
     * @param string className The name of the class to lookup the primary key
     * type for.
     * @return string The type of the field representing a class instance primary
     * key, or null if no primary key is found or defined for the class.
     * @todo Refactor method to return array of types rather than compound!
     */
    function getPKType($className, $pk= false) {
        $pktype= null;
        if ($actualClassName= $this->loadClass($className)) {
            if (!$pk)
                $pk= $this->getPK($actualClassName);
            if (is_array($pk)) {
                $pktype= 'compound';
            } else {
                $parentClass= $actualClassName;
                $pktype= isset ($this->map[$parentClass]['fieldMeta'][$pk]['phptype']) ? $this->map[$parentClass]['fieldMeta'][$pk]['phptype'] : null;
                while ($pktype === null && $parentClass= get_parent_class($parentClass)) {
                    $pktype= isset ($this->map[$parentClass]['fieldMeta'][$pk]['phptype']) ? $this->map[$parentClass]['fieldMeta'][$pk]['phptype'] : null;
                    if (strcasecmp($parentClass, 'xPDOObject') == 0) {
                        break;
                    }
                }
            }
        } else {
            $this->_log(XPDO_LOG_LEVEL_ERROR, "Could not load class {$className}!");
        }
        return $pktype;
    }

    /**
     * Gets a collection of aggregate foreign key relationship definitions.
     * 
     * @param string $className The fully-qualified name of the class.
     * @return array An array of aggregate foreign key relationship definitions.
     */
    function getAggregates($className) {
        $aggregates= array ();
        if ($className= $this->loadClass($className)) {
            if ($ancestry= $this->getAncestry($className)) {
                for ($i= count($ancestry) - 1; $i >= 0; $i--) {
                    if (isset ($this->map[$ancestry[$i]]['aggregates'])) {
                        $aggregates= array_merge($aggregates, $this->map[$ancestry[$i]]['aggregates']);
                    }
                }
            }
        }
        return $aggregates;
    }

    /**
     * Gets a collection of composite foreign key relationship definitions.
     * 
     * @param string $className The fully-qualified name of the class.
     * @return array An array of composite foreign key relationship definitions.
     */
    function getComposites($className) {
        $composites= array ();
        if ($className= $this->loadClass($className)) {
            if ($ancestry= $this->getAncestry($className)) {
                for ($i= count($ancestry) - 1; $i >= 0; $i--) {
                    if (isset ($this->map[$ancestry[$i]]['composites'])) {
                        $composites= array_merge($composites, $this->map[$ancestry[$i]]['composites']);
                    }
                }
            }
        }
        return $composites;
    }

    /**
     * Retrieves the complete ancestry for a class.
     * 
     * @param string className The name of the class.
     * @param boolean includeSelf Determines if the specified class should be
     * included in the resulting array.
     * @return array An array of string class names representing the class
     * hierarchy, or an empty array if unsuccessful.
     */
    function getAncestry($className, $includeSelf= true) {
        $ancestry= array ();
        if ($actualClassName= $this->loadClass($className)) {
            $ancestor= $actualClassName;
            if ($includeSelf) {
                $ancestry[]= $actualClassName;
            }
            while ($ancestor= get_parent_class($ancestor)) {
                $ancestry[]= $ancestor;
            }
        }
        return $ancestry;
    }

    /**
     * Loads an instance of a class based on a specified xPDOCriteria instance.
     * 
     * @param string $className Name of the class.
     * @param mixed $criteria The primary key or xPDOCriteria for loading the
     * instance.
     * @return object|null An instance of the requested class, or null if it
     * could not be instantiated.
     */
    function load($className, $criteria, $cacheFlag= false) {
        $instance= null;
        $sql= '';
        if ($className= $this->loadClass($className)) {
            $bindings= array ();
            if (!is_object($criteria)) {
                $criteria= $this->getCriteria($className, $criteria, $cacheFlag);
            }
            if (is_object($criteria) && $criteria->stmt !== null && !empty($criteria->sql)) {
                $fromCache= false;
                $row= null;
                if ($criteria->cacheFlag && $cacheFlag) {
                    $row= $this->fromCache($criteria, $className);
                }
                if ($row === null || !is_array($row)) {
                    if ($this->getDebug() === true) $this->_log(XPDO_LOG_LEVEL_DEBUG, "Attempting to execute query using PDO statement object: " . print_r($criteria->stmt, true) . print_r($criteria->bindings, true));
                    $tstart= $this->getMicroTime();
                    if (!$criteria->stmt->execute()) {
                        $tend= $this->getMicroTime();
                        $totaltime= $tend - $tstart;
                        $this->queryTime= $this->queryTime + $totaltime;
                        $this->executedQueries= $this->executedQueries + 1;
                        $errorInfo= $criteria->stmt->errorInfo();
                        $this->_log(XPDO_LOG_LEVEL_ERROR, 'Error ' . $criteria->stmt->errorCode() . " executing statement: \n" . print_r($errorInfo, true));
                        if ($errorInfo[1] == '1146') {
                            if ($this->getManager() && $this->manager->createObjectContainer($className)) {
                                $tstart= $this->getMicroTime();
                                if (!$criteria->stmt->execute()) {
                                    $this->_log(XPDO_LOG_LEVEL_FATAL, "Error " . $criteria->stmt->errorCode() . " executing statement: \n" . print_r($criteria->stmt->errorInfo(), true));
                                }
                                $tend= $this->getMicroTime();
                                $totaltime= $tend - $tstart;
                                $this->queryTime= $this->queryTime + $totaltime;
                                $this->executedQueries= $this->executedQueries + 1;
                            } else {
                                $this->_log(XPDO_LOG_LEVEL_FATAL, "Error " . $this->errorCode() . " attempting to create object container for class {$className}:\n" . print_r($this->errorInfo(), true));
                            }
                        }
                    }
                    $row= $criteria->stmt->fetch(PDO_FETCH_ASSOC);
                    $criteria->stmt->closeCursor();
                } else {
                    $fromCache= true;
                }
                if (!is_array($row)) {
                    if ($this->getDebug() === true) $this->_log(XPDO_LOG_LEVEL_DEBUG, "Error fetching result set from statement: " . print_r($criteria->stmt, true) . " with bindings: " . print_r($bindings, true));
                    $this->_log(XPDO_LOG_LEVEL_WARN, "Error fetching result set: {$criteria->stmt->queryString}" . print_r($criteria->stmt->errorInfo(), true));
                } else {
                    if (isset ($row['class_key'])) {
                        $actualClass= $row['class_key'];
                        $instance= $this->newObject($actualClass);
                        if (!is_a($instance, $className)) {
                            $this->_log(XPDO_LOG_LEVEL_WARN, "Instantiated a derived class {$actualClass} that is not a subclass of the requested class {$className}");
                        }
                    } else {
                        $instance= $this->newObject($className);
                    }
                    $instance->fromArray($row, '', true);
                }
            } else {
                $this->_log(XPDO_LOG_LEVEL_ERROR, 'No valid statement could be found in or generated from the given criteria.');
            }
        } else {
            $this->_log(XPDO_LOG_LEVEL_ERROR, 'Invalid class specified: ' . $className);
        }
        if ($instance) {
            $instance->_dirty= array ();
            $instance->_new= false;
            if ($cacheFlag && $this->_cacheEnabled && !$fromCache && is_object($criteria)) {
                $this->toCache($criteria, $instance, $cacheFlag);
            }
        }
        return $instance;
    }

    /**
     * Gets select columns from a specific class for building a query.
     * 
     * @param string $className The name of the class to build the column list
     * from.
     * @param string $tableAlias An optional alias for the class table, to be
     * used in complex queries with multiple tables.
     * @param string $columnPrefix An optional string with which to prefix the
     * columns returned, to avoid name collisions in return columns.
     * @param array $columns An optional array of columns to include.
     * @param boolean $exclude If true, will exclude columns in the previous
     * parameter, instead of including them.
     * @return string A valid SQL string of column names for a SELECT statement.
     */
    function getSelectColumns($className, $tableAlias= '', $columnPrefix= '', $columns= array (), $exclude= false) {
        $columnarray= array ();
        if ($aColumns= $this->getFields($className)) {
            $tableAlias= !empty ($tableAlias) ? $tableAlias . '.' : '';
            $this->_log(XPDO_LOG_LEVEL_WARN, print_r($aColumns, true));
            foreach (array_keys($aColumns) as $k) {
                if ($exclude && in_array($k, $columns)) {
                    continue;
                }
                elseif (empty ($columns)) {
                    $columnarray[$k]= "{$tableAlias}{$k}";
                }
                elseif (in_array($k, $columns)) {
                    $columnarray[$k]= "{$tableAlias}{$k}";
                } else {
                    continue;
                }
                if (!empty ($columnPrefix)) {
                    $columnarray[$k]= $columnarray[$k] . " AS {$columnPrefix}{$k}";
                }
            }
        }
        return implode(', ', $columnarray);
    }

    /**
     * Gets the manager class for this XPDO connection.
     * 
     * The manager class can perform operations such as creating or altering
     * table structures, creating data containers, generating custom persistence
     * classes, and other advanced operations that do not need to be loaded
     * frequently.
     * 
     * @return object|null A manager instance for the XPDO connection, or null
     * if a manager class can not be instantiated.
     */
    function getManager() {
        if ($this->manager === null || !is_a($this->manager, 'xPDOManager')) {
            if ($managerClass= $this->loadClass($this->config['dbtype'] . '.xPDOManager', '', true, true)) {
                $this->manager= new $managerClass ($this);
            } else {
                $this->_log(XPDO_LOG_LEVEL_ERROR, "Could not load xPDOManager class.");
            }
        }
        return $this->manager;
    }

    /**
     * Gets the absolute path to the cache directory.
     * 
     * @return string The full cache directory path.
     */
    function getCachePath() {
        return $this->cachePath;
    }

    /**
     * Gets the xPDOCacheManager instance.
     * 
     * This class is responsible for handling all types of caching operations
     * for the xPDO core.
     * 
     * @return object The xPDOCacheManager for this xPDO instance.
     */
    function getCacheManager() {
        if ($this->cacheManager === null) {
            if ($this->loadClass('cache.xPDOCacheManager', XPDO_CORE_PATH, true, true)) {
                if ($this->cacheManager= new xPDOCacheManager($this)) {
                    $this->_cacheEnabled= true;
                }
            }
        }
        return $this->cacheManager;
    }

    /**
     * Returns the debug state for the XPDO connection.
     * 
     * @return boolean The current debug state for the connection, true for on,
     * false for off.
     */
    function getDebug() {
        return $this->_debug;
    }

    /**
     * Sets the debug state for the XPDO connection.
     * 
     * @param boolean $v The debug status, true for on, false for off.
     */
    function setDebug($v= true) {
        $this->_debug= $v;
    }

    /**
     * Sets the logging level state for the XPDO instance.
     * 
     * @param integer $level The logging level to switch to.
     */
    function setLogLevel($level= XPDO_LOG_LEVEL_FATAL) {
        $this->logLevel= intval($level);
    }

    function setLogTarget($target= 'ECHO') {
        $this->logTarget= $target;
    }

    /**
     * Log a message as appropriate for the level and target.
     * 
     * @param integer $level The level of the logged message.
     * @param string $msg The message to log.
     * @param string $target The logging target.
     */
    function _log($level, $msg, $target= '', $def= '', $file= '', $line= '') {
//        $timestamp= strftime('%Y-%m-%d %H:%M:%S');
        if (empty ($target)) {
            $target= $this->logTarget;
        }
        if ($level === XPDO_LOG_LEVEL_FATAL) {
            exit ('[' . strftime('%Y-%m-%d %H:%M:%S') . '] (' . $this->_getLogLevel($level) . $def . $file . $line . ') ' . $msg . "\n" . ($this->getDebug() ? '<pre>' . "\n" . print_r(debug_backtrace(), true) . "\n" . '</pre>' : ''));
        }
        if ($this->_debug === true || $level <= $this->logLevel) {
            if (!empty ($def)) {
                $def= " in {$def}";
            }
            if (!empty ($file)) {
                $file= " @ {$file}";
            }
            if (!empty ($line)) {
                $line= " : {$line}";
            }
            switch ($target) {
                case 'ECHO' :
                    echo '[' . strftime('%Y-%m-%d %H:%M:%S') . '] (' . $this->_getLogLevel($level) . $def . $file . $line . ') ' . $msg . "\n";
                    break;
                case 'HTML' :
                    echo '<h5>[' . strftime('%Y-%m-%d %H:%M:%S') . '] (' . $this->_getLogLevel($level) . $def . $file . $line . ')</h5><pre>' . $msg . '</pre>' . "\n";
                    break;
                default :
                    echo '[' . strftime('%Y-%m-%d %H:%M:%S') . '] (' . $this->_getLogLevel($level) . $def . $file . $line . ') ' . $msg . "\n";
            }
        }
    }

    /**
     * Gets a logging level as a string representation.
     * 
     * @param integer $level The logging level to retrieve a string for.
     * @return string The string representation of a valid logging level.
     */
    function _getLogLevel($level) {
        $levelText= '';
        switch ($level) {
            case XPDO_LOG_LEVEL_DEBUG :
                $levelText= 'DEBUG';
                break;
            case XPDO_LOG_LEVEL_INFO :
                $levelText= 'INFO';
                break;
            case XPDO_LOG_LEVEL_WARN :
                $levelText= 'WARN';
                break;
            case XPDO_LOG_LEVEL_ERROR :
                $levelText= 'ERROR';
                break;
            default :
                $levelText= 'FATAL';
        }
        return $levelText;
    }

    /**
     * Adds the table prefix, and optionally database name, to a given table.
     * 
     * @param string $baseTableName The table name as specified in the object
     * model.
     * @param boolean $includeDb Qualify the table name with the database name.
     * @return string The fully-qualified and quoted table name for the
     */
    function _getFullTableName($baseTableName, $includeDb= true) {
        $fqn= '';
        if (!empty ($baseTableName)) {
            if ($includeDb) {
                $fqn .= '`' . $this->config['dbname'] . '`.';
            }
            $fqn .= '`' . $this->config['table_prefix'] . $baseTableName . '`';
        }
        return $fqn;
    }

    /**
     * Parses a DSN and returns an array of the connection details.
     * 
     * @param string $string The DSN to parse.
     * @return array An array of connection details from the DSN.
     * @todo Have this method handle all methods of DSN specification as handled
     * by latest native PDO implementation.
     */
    function parseDSN($string) {
        $result= array ();
        $pos= strpos($string, ':');
        $parameters= explode(';', substr($string, ($pos +1)));
        $result['dbtype']= strtolower(substr($string, 0, $pos));
        for ($a= 0, $b= count($parameters); $a < $b; $a++) {
            $tmp= explode('=', $parameters[$a]);
            if (count($tmp) == 2)
                $result[$tmp[0]]= $tmp[1];
            else
                $result['dbname']= $parameters[$a];
        }
        return $result;
    }
    
    function fromCache($signature, $class= '', $format= 'php') {
        $result= null;
        if ($signature && $cacheManager= $this->getCacheManager()) {
            if (is_object($signature) && is_a($signature, 'xPDOCriteria')) {
                $sigArray= array ();
                if ($class) $sigArray['class']= $class;
                $sigArray['hash']= md5($this->toJSON(array ($signature->sql, $signature->bindings)));
                $signature= implode('_', $sigArray);
            } elseif (strpos($signature, '_') !== false) {
                $sigArray= array ();
                $exploded= explode('_', $signature);
                if ($class && (!isset($exploded[0]) || $class !== $exploded[0])) {
                    $sigArray['class']= $class;
                } elseif (isset ($exploded[0])) {
                    $sigArray['class']= $exploded[0];
                }
                $sigArray['hash']= md5($this->toJSON(array (array_diff($sigArray, $exploded))));
                $signature= implode('_', $sigArray);
            }
            if (is_string($signature)) {
                $result= $cacheManager->get($signature);
            }
            if ($format == 'json') {
                $result= $this->toJSON($result);
            }
        }
        return $result;
    }
    
    function toCache($signature, $object, $lifetime= 0, $compressed= false) {
        $result= false;
        if ($cacheManager= $this->getCacheManager()) {
            if (is_object($signature) && is_a($signature, 'xPDOCriteria')) {
                $sigArray= array();
                if (is_object($object) && $class= get_class($object)) {
                    $object->_cacheFlag= true;
                    $sigArray['class']= $class;
                }
                $sigArray['hash']= md5($this->toJSON(array ($signature->sql, $signature->bindings)));
                $signature= implode('_', $sigArray);
            } elseif (strpos($signature, '_') !== false) {
                $sigArray= array ();
                $exploded= explode('_', $signature);
                if (is_object($object) && $class= get_class($object)) {
                    if (!isset($exploded[0]) || $class !== $exploded[0]) {
                        $sigArray['class']= $class;
                    }
                }
                if (!isset ($sigArray['class']) && isset ($exploded[0])) {
                    $sigArray['class']= $exploded[0];
                }
                $sigArray['hash']= md5($this->toJSON(array (array_diff($sigArray, $exploded))));
                $signature= implode('_', $sigArray);
            }
            if (is_string($signature)) {
                $result= $cacheManager->set($signature, $object, $lifetime, $compressed);
            }
        }
        return $result;
    }
    
    function toJSON($array) {
        $encoded= '';
        if (is_array ($array)) {
            if (!function_exists('json_encode')) {
                if (@ include_once (XPDO_CORE_PATH . 'json/JSON.php')) {
                    $json = new Services_JSON();
                    $encoded= $json->encode($array);
                }
            } else {
                $encoded= json_encode($array);
            }
        }
        return $encoded;
    }

    function fromJSON($src, $asArray= true) {
        $decoded= '';
        if ($src) {
            if (!function_exists('json_decode')) {
                if (@ include_once (XPDO_CORE_PATH . 'json/JSON.php')) {
                    $json = new Services_JSON();
                    $decoded= $json->decode($src);
                    if (is_object($decoded) && $asArray) {
                        $decoded= get_object_vars($decoded);
                    }
                }
            } else {
                $decoded= json_decode($src, $asArray);
            }
        }
        return $decoded;
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-begintransaction.php
     */
    function beginTransaction() {
        $this->connect();
        $this->pdo->beginTransaction();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-commit.php
     */
    function commit() {
        $this->connect();
        $this->pdo->commit();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-exec.php
     */
    function exec($query) {
        $this->connect();
        $tstart= $this->getMicroTime();
        $return= $this->pdo->exec($query);
        $tend= $this->getMicroTime();
        $totaltime= $tend - $tstart;
        $this->queryTime= $this->queryTime + $totaltime;
        $this->executedQueries= $this->executedQueries + 1;
        return $return;
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-errorcode.php
     */
    function errorCode() {
        $this->connect();
        return $this->pdo->errorCode();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-errorinfo.php
     */
    function errorInfo() {
        $this->connect();
        return $this->pdo->errorInfo();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-getattribute.php
     */
    function getAttribute($attribute) {
        $this->connect();
        return $this->pdo->getAttribute($attribute);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-lastinsertid.php
     */
    function lastInsertId() {
        $this->connect();
        return $this->pdo->lastInsertId();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-prepare.php
     */
    function prepare($statement, $driver_options= array ()) {
        $this->connect();
        return $this->pdo->prepare($statement, $driver_options= array ());
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-query.php
     */
    function query($query) {
        $this->connect();
        $tstart= $this->getMicroTime();
        $return= $this->pdo->query($query);
        $tend= $this->getMicroTime();
        $totaltime= $tend - $tstart;
        $this->queryTime= $this->queryTime + $totaltime;
        $this->executedQueries= $this->executedQueries + 1;
        return $return;
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-quote.php
     */
    function quote($string, $parameter_type= PDO_PARAM_STR) {
        $this->connect();
        return $this->pdo->quote($string, $parameter_type);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-rollback.php
     */
    function rollBack() {
        $this->connect();
        $this->pdo->rollBack();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-setattribute.php
     */
    function setAttribute($attribute, $value) {
        $this->connect();
        return $this->pdo->setAttribute($attribute, $value);
    }
    
    function getMicroTime() {
       list($usec, $sec) = explode(' ', microtime());
       return ((float)$usec + (float)$sec);
    }
}

/**
 * Encapsulates a PDOStatement and a set of bindings.
 * 
 * @package xpdo
 */
class xPDOCriteria {
    var $sql= '';
    var $stmt= null;
    var $bindings= array ();
    var $cacheFlag= false;

    /**
     * Creates a new xPDOCriteria instance.
     * 
     * The constructor optionally prepares provided SQL and/or parameter
     * bindings.  Setting the bindings via the constructor or with the {@link
     * xPDOCriteria::bind()} function allows you to make use of the data object
     * caching layer.
     * 
     * @todo Refactor this so you can get an xPDOCriteria instance without
     * calling the prepare and bind methods explicitly.  In this way, the
     * criteria can be constructed without the database connection required.
     * Without this, every request will require a database connection before
     * even looking at the object/result set cache.
     * 
     * @param string $sql The SQL statement.
     * @param array $bindings Bindings to bind to the criteria.
     * @param boolean|integer $cacheFlag Indicates if the result set from the
     * criteria is to be cached (true|false) and how long it will live in cache
     * in seconds.
     */
    function xPDOCriteria(& $xpdo, $sql= '', $bindings= array (), $cacheFlag= false) {
        $this->xpdo= & $xpdo;
        $this->cacheFlag= $cacheFlag;
        if (is_string($sql) && !empty ($sql)) {
            $this->sql= $sql;
            $this->stmt= $xpdo->prepare($sql);
            if (!empty ($bindings)) {
                $this->bind($bindings, true, $cacheFlag);
            }
        }
    }
    
    /**
     * Binds an array of key/value pairs to the xPDOCriteria prepared statement.
     * 
     * Use this method to bind parameters in a way that makes it possible to
     * cache results of previous executions of the criteria or compare the
     * criteria to other individual or collections of criteria.
     * 
     * @param array $bindings Bindings to merge with any existing bindings
     * defined for this xPDOCriteria instance.  Bindings can be simple
     * associative array of key-value pairs or the value for each key can
     * contain elements titled value, type, and length corresponding to the
     * appropriate parameters in the PDOStatement::bindValue() and
     * PDOStatement::bindParam() functions.
     * @param boolean $byValue Determines if the $bindings are to be bound as
     * parameters (by variable reference, the default behavior) or by direct
     * value (if true).
     * @param boolean|integer $cacheFlag The cacheFlag indicates the cache state
     * of the xPDOCriteria object and can be absolutely off (false), absolutely 
     */
    function bind($bindings= array (), $byValue= true, $cacheFlag= false) {
        if (!empty ($bindings)) {
            $this->bindings= array_merge($this->bindings, $bindings);
        }
        if (is_object($this->stmt) && $this->stmt && !empty ($this->bindings)) {
            reset($this->bindings);
            while (list ($key, $val)= each($this->bindings)) {
                if (is_array($val)) {
                    $type= isset ($val['type']) ? $val['type'] : PDO_PARAM_STR;
                    $length= isset ($val['length']) ? $val['length'] : 0;
                    $value= & $val['value'];
                } else {
                    $value= & $val;
                    $type= PDO_PARAM_STR;
                    $length= 0;
                }
                if ($byValue) {
                    $this->stmt->bindValue($key, $value, $type);
                } else {
                    $this->stmt->bindParam($key, $value, $type, $length);
                }
            }
        }
        $this->cacheFlag= $cacheFlag;
    }

    /**
     * Compares to see if two xPDOCriteria instances are the same.
     * 
     * @param object $obj A xPDOCriteria object to compare to this one.
     * @return boolean true if they are both equal is SQL and bindings, otherwise
     * false.
     */
    function equals($obj) {
        return (is_object($obj) && is_a($obj, 'xPDOCriteria') && $this->sql === $obj->sql && !array_diff_assoc($this->bindings, $obj->bindings));
    }
}
?>
