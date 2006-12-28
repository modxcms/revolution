<?php
/*
 * OpenExpedio (xPDO)
 * Copyright (C) 2006 Jason Coward <xpdo@opengeek. com>
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
 * Contains the classes required to support SQLite PDO when native PDO is not
 * available to the platform.
 * 
 * @package xpdo
 */

/**
 * A SQLite implementation of PDO for PHP 4 and 5.0.x.
 * 
 * @package xpdo
 */
class PDO_sqlite {
    var $_connection;
    var $_dbinfo;
    var $_persistent= false;
    var $_errorCode= '';
    var $_errorInfo= array (
        ''
    );

    function PDO_sqlite(& $string_dsn) {
        if (!@ $this->_connection= sqlite_open($string_dsn))
            $this->_setErrors('DBCON', true);
        else
            $this->_dbinfo= & $string_dsn;
    }

    function errorCode() {
        return $this->_errorCode;
    }

    function errorInfo() {
        return $this->_errorInfo;
    }

    function exec($query) {
        $result= 0;
        if (!is_null($this->_uquery($query)))
            $result= sqlite_changes($this->_connection);
        if (is_null($result))
            $result= false;
        return $result;
    }

    function lastInsertId() {
        return sqlite_last_insert_rowid($this->_connection);
    }

    function prepare($statement, $driver_options= array ()) {
        return new PDOStatement_sqlite($statement, $this->_connection, $this->_dbinfo);
    }

    function query($statement) {
        $args= func_get_args();
        $result= false;
        if ($stmt= new PDOStatement_sqlite($statement, $this->_connection, $this->_dbinfo)) {
            if (count($args) > 1) {
                $stmt->setFetchMode($args[1]);
            }
            $stmt->execute();
            $result= & $stmt;
        }
        return $result;
    }

    function quote($string, $parameter_type= PDO_PARAM_STR) {
        $string= sqlite_escape_string($string);
        switch ($parameter_type) {
            case PDO_PARAM_NULL:
                break;
            case PDO_PARAM_INT:
                break;
            default:
                $string= "'" . $string . "'";
        }
        return ;
    }

    function getAttribute($attribute) {
        $result= null;
        switch ($attribute) {
            case PDO_ATTR_SERVER_INFO :
                $result= sqlite_libencoding();
                break;
            case PDO_ATTR_SERVER_VERSION :
                break;
            case PDO_ATTR_CLIENT_VERSION :
                $result= sqlite_libversion();
                break;
            case PDO_ATTR_PERSISTENT :
                $result= $this->_persistent;
                break;
            case PDO_ATTR_DRIVER_NAME :
                $result= 'sqlite';
                break;
        }
        return $result;
    }

    function setAttribute($attribute, $value) {
        $result= false;
        if ($attribute === PDO_ATTR_PERSISTENT && $value != $this->_persistent) {
            $result= true;
            $this->_persistent= (boolean) $value;
            sqlite_close($this->_connection);
            if ($this->_persistent === true) {
                $this->_connection= sqlite_popen($this->_dbinfo);
            }
            else {
                $this->_connection= sqlite_open($this->_dbinfo);
            }
        }
        return $result;
    }

    function beginTransaction() {
        return false;
    }

    function commit() {
        return false;
    }

    function rollBack() {
        return false;
    }

    function _setErrors($er, $connection= false) {
        if (!is_resource($this->_connection)) {
            $errno= 1;
            $errst= 'Unable to open or find database.';
        } else {
            $errno= sqlite_last_error($this->_connection);
            $errst= sqlite_error_string($errno);
        }
        $this->_errorCode= & $er;
        $this->_errorInfo= array (
            $this->_errorCode,
            $errno,
            $errst
        );
    }

    function _uquery(& $query) {
        if (!@ $query= sqlite_query($query, $this->_connection)) {
            $this->_setErrors('SQLER');
            $query= null;
        }
        return $query;
    }
}

/**
 * A SQLite PDOStatement class compatible with PHP 4.
 * 
 * @package xpdo
 */
class PDOStatement_sqlite extends PDOStatement {
    function PDOStatement_sqlite($queryString, & $connection, & $dbinfo) {
        $this->__construct($queryString, $connection, $dbinfo);
    }
    function __construct($queryString, & $connection, & $dbinfo) {
        parent :: __construct($queryString, $connection, $dbinfo);
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

    function columnCount() {
        $result= 0;
        if (!is_null($this->_result))
            $result= sqlite_num_fields($this->_result);
        return $result;
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
                    $parse= create_function('$d,$v,$t', 'return $d->quote($v,$t);');
                    $queryString= preg_replace("/(\?)/e", '$parse($this,$array[$k],$type);', $queryString, 1);
                }
            }
            if (isset ($tempf)) {
                $queryString= str_replace($tempf, $tempr, $queryString);
            }
        }
        if (is_null($this->_result= $this->_uquery($queryString))) {
            $keyvars= false;
        } else {
            $keyvars= true;
        }
        return $keyvars;
    }

    function fetch($mode= PDO_FETCH_BOTH, $cursor= null, $offset= null) {
        if (func_num_args() == 0)
            $mode= & $this->_fetchmode;
        $result= false;
        if (!is_null($this->_result)) {
            switch ($mode) {
                case PDO_FETCH_NUM :
                    $result= @ sqlite_fetch_array($this->_result, SQLITE_NUM);
                    break;
                case PDO_FETCH_ASSOC :
                    $result= @ sqlite_fetch_array($this->_result, SQLITE_ASSOC);
                    break;
                case PDO_FETCH_OBJ :
                    $result= @ sqlite_fetch_object($this->_result);
                    break;
                case PDO_FETCH_BOTH :
                default :
                    $result= @ sqlite_fetch_array($this->_result, SQLITE_BOTH);
                    break;
            }
        }
        if (!$result)
            $this->_result= null;
        return $result;
    }

    function fetchAll($mode= PDO_FETCH_BOTH, $column_index= 0) {
        $result= array ();
        if (!is_null($this->_result)) {
            switch ($mode) {
                case PDO_FETCH_NUM :
                    while ($r= @sqlite_fetch_array($this->_result, SQLITE_NUM))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_COLUMN :
                    while ($r= @ sqlite_fetch_array($this->_result, SQLITE_NUM))
                        array_push($result, $r[$column_index]);
                    break;
                case PDO_FETCH_ASSOC :
                    while ($r= @ sqlite_fetch_array($this->_result, SQLITE_ASSOC))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_OBJ :
                    while ($r= @ sqlite_fetch_object($this->_result))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_BOTH :
                default :
                    while ($r= @ sqlite_fetch_array($this->_result, SQLITE_BOTH))
                        array_push($result, $r);
                    break;
            }
        }
        $this->_result= null;
        return $result;
    }

    function fetchColumn() {
        $result= null;
        if (!is_null($this->_result)) {
            $result= @ sqlite_fetch_array($this->_result, SQLITE_NUM);
            if ($result)
                $result= $result[0];
            else
                $this->_result= null;
        }
        return $result;
    }

    function rowCount() {
        return sqlite_changes($this->_connection);
    }

    function getAttribute($attribute) {
        $result= null;
        switch ($attribute) {
            case PDO_ATTR_SERVER_INFO :
                $result= sqlite_libencoding();
                break;
            case PDO_ATTR_SERVER_VERSION :
                break;
            case PDO_ATTR_CLIENT_VERSION :
                $result= sqlite_libversion();
                break;
            case PDO_ATTR_PERSISTENT :
                $result= $this->_persistent;
                break;
            case PDO_ATTR_DRIVER_NAME :
                $result= 'sqlite';
                break;
        }
        return $result;
    }

    function setAttribute($attribute, $value) {
        $result= false;
        if ($attribute === PDO_ATTR_PERSISTENT && $value != $this->_persistent) {
            $result= true;
            $this->_persistent= (boolean) $value;
            sqlite_close($this->_connection);
            if ($this->_persistent === true) {
                $this->_connection= sqlite_popen($this->_dbinfo);
            }
            else {
                $this->_connection= sqlite_open($this->_dbinfo);
            }
        }
        return $result;
    }

    function setFetchMode($mode) {
        $result= false;
        switch ($mode) {
            case PDO_FETCH_NUM :
            case PDO_FETCH_ASSOC :
            case PDO_FETCH_OBJ :
            case PDO_FETCH_BOTH :
                $result= true;
                $this->_fetchmode= & $mode;
                break;
        }
        return $result;
    }

    function bindColumn($column, & $param, $type= PDO_PARAM_STR) {
        return false;
    }

    function _setErrors($er, $connection= false) {
        if (!is_resource($this->_connection)) {
            $errno= 1;
            $errst= 'Unable to open database.';
        } else {
            $errno= sqlite_last_error($this->_connection);
            $errst= sqlite_error_string($errno);
        }
        $this->_errorCode= & $er;
        $this->_errorInfo= array (
            $this->_errorCode,
            $errno,
            $errst
        );
        $this->_result= null;
    }

    function _uquery(& $query) {
        if (!@ $query= sqlite_query($query, $this->_connection)) {
            $this->_setErrors('SQLER');
            $query= null;
        }
        return $query;
    }

    function quote($string, $parameter_type= PDO_PARAM_STR) {
        $string= sqlite_escape_string($string);
        switch ($parameter_type) {
            case PDO_PARAM_NULL:
                break;
            case PDO_PARAM_INT:
                break;
            default:
                $string= "'" . $string . "'";
        }
        return ;
    }

    function closeCursor() {
        do {
           while ($this->fetch()) {}
           if (!$this->nextRowset())
               break;
        } while (true);
    }
    
    function nextRowset() {
        return false;
    }
}
?>