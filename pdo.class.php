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
 * The main class for the PDO for PHP 4 implementation.
 * 
 * Provides limited PHP 4 compatible {@link PDO} and {@link PDOStatement}
 * implementations for PHP 4 (and 5.0.x platforms without the PECL PDO
 * extensions installed).
 * 
 * @package xpdo
 */

/**
 * The PHP Data Objects (PDO) extension defines a lightweight, consistent
 * interface for accessing databases in PHP. Each database driver that
 * implements the PDO interface can expose database-specific features as regular
 * extension functions. Note that you cannot perform any database functions
 * using the PDO extension by itself; you must use a database-specific PDO
 * driver to access a database server.  See the {@link http://php.net/xpdo
 * official PDO documentation} for more detailed information on PDO and how it
 * is used.
 * 
 * This PDO implementation is provided by OpenExpedio (XPDO) to allow
 * compatibility with PHP 4.3+ when native PDO is not available (PHP 5.1+).
 * 
 * @package xpdo
 * @see http://php.net/xpdo
 * @todo Implement additional driver support; currently only supports MySQL,
 * PostgreSQL, and SQLite
 */
class PDO {
    var $_driver;
    var $_dbtype= null;

    /**
     * Returns a list of available database drivers.
     * 
     * @return array All drivers available to any PDO instance.
     * @todo Implement this as a dynamic list of available drivers.
     */
    function getAvailableDrivers() {
        return array (
            'mysql',
            'pgsql',
            'sqlite'
        );
    }

    /**
     * Creates a PDO instance to represent a connection to the requested
     * database. This is the public constructor for PHP 4 support; falls through
     * to PHP 5 style constructor, and is ignored by PHP 5.
     * 
     * @see __construct
     */
    function PDO($string_dsn, $string_username= '', $string_password= '', $array_driver_options= null) {
        $this->__construct($string_dsn, $string_username, $string_password, $array_driver_options);
    }
    /**
     * Creates a PDO instance to represent a connection to the requested database.
     * 
     * @see http://php.net/manual/en/function.pdo-construct.php
     * @param string $dsn The Data Source Name, or DSN, contains the information
     * required to connect to the database.
     * @param string $username The user name for the DSN string. This parameter
     * is optional for some PDO drivers.
     * @param string $password The password for the DSN string. This parameter
     * is optional for some PDO drivers.
     * @param array $driver_options A key=>value array of driver-specific
     * connection options.
     * @return A valid PDO instance if successful; error/exception is raised
     * otherwise.
     */
    function __construct($string_dsn, $string_username= '', $string_password= '', $array_driver_options= null) {
        $con= xPDO :: parseDSN($string_dsn);
        $driverClass= XPDO_CORE_PATH . 'pdo.' . $con['dbtype'] . '.inc.php';
        include_once (XPDO_CORE_PATH . 'pdo.' . $con['dbtype'] . '.inc.php');
        $this->_dbtype= $con['dbtype'];
        if ($con['dbtype'] === 'mysql') {
            if (isset ($con['port']))
                $con['host'] .= ':' . $con['port'];
            $this->_driver= new PDO_mysql($con['host'], $con['dbname'], $string_username, $string_password);
        }
        elseif ($con['dbtype'] === 'sqlite2' || $con['dbtype'] === 'sqlite') {
            $this->_driver= new PDO_sqlite($con['dbname']);
        }
        elseif ($con['dbtype'] === 'pgsql') {
            $string_dsn= 'host=' . $con['host'] . ' dbname=' . $con['dbname'] . ' user=' . $string_username . ' password=' . $string_password;
            if (isset ($con['port']))
                $string_dsn .= ' port=' . $con['port'];
            $this->_driver= new PDO_pgsql($string_dsn);
        }
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-begintransaction.php
     */
    function beginTransaction() {
        $this->_driver->beginTransaction();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-commit.php
     */
    function commit() {
        $this->_driver->commit();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-exec.php
     */
    function exec($query) {
        return $this->_driver->exec($query);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-errorcode.php
     */
    function errorCode() {
        return $this->_driver->errorCode();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-errorinfo.php
     */
    function errorInfo() {
        return $this->_driver->errorInfo();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-getattribute.php
     */
    function getAttribute($attribute) {
        return $this->_driver->getAttribute($attribute);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-lastinsertid.php
     */
    function lastInsertId() {
        return $this->_driver->lastInsertId();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-prepare.php
     */
    function prepare($statement, $driver_options= array ()) {
        return $this->_driver->prepare($statement, $driver_options= array ());
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-query.php
     */
    function query($query) {
        return $this->_driver->query($query);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-quote.php
     */
    function quote($string, $parameter_type= PDO_PARAM_STR) {
        return $this->_driver->quote($string, $parameter_type);
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-rollback.php
     */
    function rollBack() {
        $this->_driver->rollBack();
    }

    /** 
     * @see http://php.net/manual/en/function.pdo-setattribute.php
     */
    function setAttribute($attribute, $value) {
        return $this->_driver->setAttribute($attribute, $value);
    }
}

/**
 * Represents a PDO prepared statement.
 */
class PDOStatement {
    /**
     * @var string The SQL query string for the statement to use.
     */
    var $queryString= '';

    var $_connection;
    var $_dbinfo;
    var $_persistent= false;
    var $_result= null;
    var $_fetchmode= PDO_FETCH_BOTH;
    var $_errorCode= '';
    var $_errorInfo= array (
        PDO_ERR_NONE
    );
    var $_boundParams= array ();
    
    function PDOStatement($queryString, & $connection, & $dbinfo) {
        $this->__construct($queryString, $connection, $dbinfo);
    }
    function __construct($queryString, & $connection, & $dbinfo) {
        $this->queryString= $queryString;
        $this->_connection= & $connection;
        $this->_dbinfo= & $dbinfo;
    }

    function bindParam($param, & $variable, $data_type= PDO_PARAM_STR, $length= 0, $driver_options= null) {
        $this->_boundParams[$param]['value']= $variable;
        $this->_boundParams[$param]['type']= $data_type;
        $this->_boundParams[$param]['length']= intval($length);
    }

    function bindValue($param, $value, $data_type= PDO_PARAM_STR) {
        $this->_boundParams[$param]['value']= $value;
        $this->_boundParams[$param]['type']= $data_type;
        $this->_boundParams[$param]['length']= 0;
    }

    function errorCode() {
        return $this->_errorCode;
    }

    function errorInfo() {
        return $this->_errorInfo;
    }

    function execute($input_parameters= null) {
        $array= & $this->_boundParams;
        if (is_array($input_parameters) && !empty ($input_parameters)) {
            $array= $input_parameters;
        }
        $queryString= $this->queryString;
        if (count($array) > 0) {
            reset($array);
            while (list ($k, $param)= each($array)) {
                $v= $param['value'];
                $type= $param['type'];
                if (!is_int($k) || substr($k, 0, 1) === ':') {
                    if (!isset ($tempf)) {
                        $tempf= $tempr= array ();
                    }
                    $pattern= '/' . $k . '\b/';
                    array_push($tempf, $pattern);
                    $v= $this->quote($v, $type);
                    array_push($tempr, $v);
                } else {
                    $parse= create_function('$d,$v,$t', 'return $d->quote($v, $t);');
                    $queryString= preg_replace("/(\?)/e", '$parse($this,$array[$k],$type);', $queryString, 1);
                }
            }
            if (isset ($tempf)) {
                $queryString= preg_replace($tempf, $tempr, $queryString);
            }
        }
        if (is_null($this->_result= $this->_uquery($queryString))) {
            $keyvars= false;
        } else {
            $keyvars= true;
        }
        return $keyvars;
    }

    function setFetchMode($mode) {
        $result= false;
        if ($mode= intval($mode)) {
            switch ($mode) {
                case PDO_FETCH_NUM :
                case PDO_FETCH_ASSOC :
                case PDO_FETCH_OBJ :
                case PDO_FETCH_BOTH :
                default:
                    $result= true;
                    $this->_fetchmode= $mode;
                    break;
            }
        }
        return $result;
    }

    function closeCursor() {
        do {
           while ($this->fetch()) {}
           if (!$this->nextRowset())
               break;
        } while (true);
    }

    function bindColumn($column, & $param, $type= null, $max_length= null, $driver_option= null) { return false; }

    function columnCount() { return false; }

    function getAttribute($attribute) { return false; }

    function getColumnMeta($column) { return false; }

    function fetch($mode= PDO_FETCH_BOTH, $cursor= null, $offset= null) { return false; }

    function fetchAll($mode= PDO_FETCH_BOTH, $column_index= 0) { return false; }

    function fetchColumn($column_number= 0) { return false; }

    function fetchObject($class_name= '', $ctor_args= null) { return false; }
    
    function nextRowset() { return false; }

    function quote($string, $parameter_type= PDO_PARAM_STR) { return false; }
    
    function rowCount() { return false; }

    function setAttribute($attribute, $value) { return false; }
    
    function debugDumpParams() { return false; }

    function _setErrors($er) { return false; }

    function _uquery(& $query) { return false; }
}
?>
