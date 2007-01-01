<?php
/*
 * OpenExpedio (XPDO) is an ultra-light, PHP 5.1+ compatible ORB (Object-
 * Relational Bridge) library based around PDO (http://php.net/pdo/).
 * 
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
 * It defines the core classes used throughout the framework.
 *  
 * @package xpdo
 */
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
    define('XPDO_LOG_LEVEL_FATAL', 0);
    define('XPDO_LOG_LEVEL_ERROR', 1);
    define('XPDO_LOG_LEVEL_WARN', 2);
    define('XPDO_LOG_LEVEL_INFO', 3);
    define('XPDO_LOG_LEVEL_DEBUG', 4);
    /**#@-*/
}

if (!class_exists('PDO')) {
    //@todo Handle PDO configuration errors here.
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
    /**
     * A PDO instance used by xPDO for database access.
     * @var PDO
     * @access protected
     */
    protected $pdo= null;
    /**
     * A array of xPDO configuration attributes.
     * @var array
     * @access public
     */
    public $config= null;
    /**
     * A map of data source meta data for all loaded classes.
     * @var array
     * @access public
     */
    public $map= array ();
    /**
     * A default package for specifying classes by name.
     * @var string
     * @access public
     */
    protected $package= '';
    /**
     * {@link xPDOManager} instance, loaded only if needed to manage datasource
     * containers, data structures, etc.
     * @var xPDOManager
     * @access public
     */
    protected $manager= null;
    /**
     * @var xPDOCacheManager The cache service provider registered for this xPDO
     * instance.
     */
    protected $cacheManager= null;
    /**
     * @var float Start time of the request, initialized when the constructor is
     * called.
     */
    protected $startTime= 0;
    /**
     * @var int The number of direct DB queries executed during a request.
     */
    protected $executedQueries= 0;
    /**
     * @var int The amount of request handling time spent with DB queries.
     */
    protected $queryTime= 0;

    /**
     * @var integer The logging level for the XPDO instance.
     */
    protected $logLevel= XPDO_LOG_LEVEL_FATAL;

    /**
     * @var string The default logging target for the XPDO instance.
     */
    protected $logTarget= 'ECHO';

    protected $_debug= false;
    protected $_nativeMode= true;
    protected $_cacheEnabled= false;

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
    public function xPDO($dsn, $username= '', $password= '', $tablePrefix= '', $driverOptions= null) {
        $this->config= $this->parseDSN($dsn);
        $this->config['dsn']= $dsn;
        $this->config['username']= $username;
        $this->config['password']= $password;
        $this->config['table_prefix']= $tablePrefix;
        $this->config['driverOptions']= $driverOptions;
        $this->loadClass('xPDOObject');
        $this->loadClass('xPDOSimpleObject');
    }
    
    protected function connect($driverOptions= array ()) {
        if ($this->pdo === null) {
            if (!empty ($driverOptions)) {
                $this->config['driverOptions']= array_merge($this->config['driverOptions'], $driverOptions);
            }
            $this->pdo= new PDO($this->config['dsn'], $this->config['username'], $this->config['password'], $this->config['driverOptions']);
            if ($this->config['dbtype'] === null) {
                $this->config['dbtype']= $this->getAttribute(PDO_ATTR_DRIVER_NAME);
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
    public function setPackage($pkg= '') {
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
    public function loadClass($fqn, $path= '', $ignorePkg= false, $transient= false) {
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
        $included= class_exists($class, false);
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
    public function newObject($className, $fields= array ()) {
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
    public function getObject($className, $criteria= null, $cacheFlag= false) {
        $instance= null;
        if ($criteria !== null) {
            $instance= xPDOObject :: load($this, $className, $criteria, $cacheFlag);
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
    public function getCollection($className, $criteria= null, $cacheFlag= false) {
        $objCollection= null;
        if ($className= $this->loadClass($className)) {
            $objCollection= xPDOObject :: loadCollection($this, $className, $criteria, $cacheFlag);
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
    public function getCriteria($className, $type= 'collection', $cacheFlag= true) {
        $criteria= null;
        if ($className= $this->loadClass($className)) {
            $criteria= xPDOObject :: loadCriteria($this, $className, $type, $cacheFlag);
        }
        if ($this->getDebug() === true) {
            $this->_log(XPDO_LOG_LEVEL_DEBUG, 'Retrieving criteria object: ' . print_r($criteria, true));
        }
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
    public function getTableName($className, $includeDb= true) {
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
    public function getTableType($className) {
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
    public function getFields($className) {
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
    public function getFieldMeta($className) {
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
    public function getPK($className) {
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
    public function getPKType($className, $pk= false) {
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
    public function getAggregates($className) {
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
    public function getComposites($className) {
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
    public function getAncestry($className, $includeSelf= true) {
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
    public function getSelectColumns($className, $tableAlias= '', $columnPrefix= '', $columns= array (), $exclude= false) {
        return xPDOObject :: getSelectColumns($this, $className, $tableAlias, $columnPrefix, $columns, $exclude);
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
    public function getManager() {
        if ($this->manager === null || !$this->manager instanceof xPDOManager) {
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
    public function getCachePath() {
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
    public function getCacheManager() {
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
    public function getDebug() {
        return $this->_debug;
    }

    /**
     * Sets the debug state for the XPDO connection.
     * 
     * @param boolean $v The debug status, true for on, false for off.
     */
    public function setDebug($v= true) {
        $this->_debug= $v;
    }

    /**
     * Sets the logging level state for the XPDO instance.
     * 
     * @param integer $level The logging level to switch to.
     */
    public function setLogLevel($level= XPDO_LOG_LEVEL_FATAL) {
        $this->logLevel= intval($level);
    }

    public function setLogTarget($target= 'ECHO') {
        $this->logTarget= $target;
    }

    /**
     * Log a message as appropriate for the level and target.
     * 
     * @param integer $level The level of the logged message.
     * @param string $msg The message to log.
     * @param string $target The logging target.
     */
    public function log($level, $msg, $target= '', $def= '', $file= '', $line= '') {
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
    protected function getLogLevel($level) {
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
    private function _getFullTableName($baseTableName, $includeDb= true) {
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
    protected function parseDSN($string) {
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
    
    protected function fromCache($signature, $class= '', $format= 'php') {
        $result= null;
        if ($signature && $cacheManager= $this->getCacheManager()) {
            if (is_object($signature) && $signature instanceof xPDOCriteria) {
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
    
    protected function toCache($signature, $object, $lifetime= 0, $compressed= false) {
        $result= false;
        if ($cacheManager= $this->getCacheManager()) {
            if (is_object($signature) && $signature instanceof xPDOCriteria) {
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
    
    public function toJSON($array) {
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

    public function fromJSON($src, $asArray= true) {
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
    public function beginTransaction() {
        $this->connect();
        $this->pdo->beginTransaction();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-commit.php
     */
    public function commit() {
        $this->connect();
        $this->pdo->commit();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-exec.php
     */
    public function exec($query) {
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
    public function errorCode() {
        $this->connect();
        return $this->pdo->errorCode();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-errorinfo.php
     */
    public function errorInfo() {
        $this->connect();
        return $this->pdo->errorInfo();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-getattribute.php
     */
    public function getAttribute($attribute) {
        $this->connect();
        return $this->pdo->getAttribute($attribute);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-lastinsertid.php
     */
    public function lastInsertId() {
        $this->connect();
        return $this->pdo->lastInsertId();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-prepare.php
     */
    public function prepare($statement, $driver_options= array ()) {
        $this->connect();
        return $this->pdo->prepare($statement, $driver_options= array ());
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-query.php
     */
    public function query($query) {
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
    public function quote($string, $parameter_type= PDO_PARAM_STR) {
        $this->connect();
        return $this->pdo->quote($string, $parameter_type);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-rollback.php
     */
    public function rollBack() {
        $this->connect();
        $this->pdo->rollBack();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-setattribute.php
     */
    public function setAttribute($attribute, $value) {
        $this->connect();
        return $this->pdo->setAttribute($attribute, $value);
    }
    
    public function getMicroTime() {
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
    protected $sql= '';
    public $stmt= null;
    public $bindings= array ();
    protected $cacheFlag= false;

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
    public function xPDOCriteria(& $xpdo, $sql= '', $bindings= array (), $cacheFlag= false) {
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
    public function bind($bindings= array (), $byValue= true, $cacheFlag= false) {
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
    public function equals($obj) {
        return (is_object($obj) && $obj instanceof xPDOCriteria && $this->sql === $obj->sql && !array_diff_assoc($this->bindings, $obj->bindings));
    }
}