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
 * Contains the classes required to support PostgreSQL PDO when native PDO is
 * not available to the platform.
 * 
 * @package xpdo
 */

/**
 * A PostgreSQL implementation of PDO for PHP 4 and 5.0.x.
 * 
 * @package xpdo
 */
class PDO_pgsql {
    var $_connection;
    var $_dbinfo;
    var $_persistent= false;
    var $_errorCode= '';
    var $_errorInfo= array (
        ''
    );
    var $_result= null;

    function PDO_pgsql(& $string_dsn) {
        if (!@ $this->_connection= pg_connect($string_dsn))
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
        $this->_uquery($query);
        if (!is_null($this->_result))
            $result= pg_affected_rows($this->_result);
        if (is_null($result))
            $result= false;
        return $result;
    }

    function lastInsertId() {
        $result= 0;
        if (!is_null($this->_result))
            $result= pg_last_oid($this->_result);
        return $result;
    }

    function prepare($statement, $driver_options= array ()) {
        return new PDOStatement_pgsql($statement, $this->_connection, $this->_dbinfo);
    }

    function query($statement) {
        $args= func_get_args();
        $result= false;
        if ($stmt= new PDOStatement_pgsql($statement, $this->_connection, $this->_dbinfo)) {
            if (count($args) > 1) {
                $stmt->setFetchMode($args[1]);
            }
            $stmt->execute();
            $result= & $stmt;
        }
        return $result;
    }

    function quote($string, $parameter_type= PDO_PARAM_STR) {
        $string= pg_escape_string();
        switch ($parameter_type) {
            case PDO_PARAM_NULL:
                break;
            case PDO_PARAM_INT:
                break;
            default:
                $string= "'" . $string . "'";
        }
        return $string;
    }

    function getAttribute($attribute) {
        $result= false;
        switch ($attribute) {
            case PDO_ATTR_SERVER_INFO :
                $result= pg_parameter_status($this->_connection, 'server_encoding');
                break;
            case PDO_ATTR_SERVER_VERSION :
                $result= pg_parameter_status($this->_connection, 'server_version');
                break;
            case PDO_ATTR_CLIENT_VERSION :
                $result= pg_parameter_status($this->_connection, 'server_version');
                $result .= ' ' . pg_parameter_status($this->_connection, 'client_encoding');
                break;
            case PDO_ATTR_PERSISTENT :
                $result= $this->_persistent;
                break;
            case PDO_ATTR_DRIVER_NAME :
                $result= 'pgsql';
                break;
        }
        return $result;
    }

    function setAttribute($attribute, $value) {
        $result= false;
        if ($attribute === PDO_ATTR_PERSISTENT && $value != $this->_persistent) {
            $result= true;
            $this->_persistent= (boolean) $value;
            pg_close($this->_connection);
            if ($this->_persistent === true) {
                $this->_connection= pg_connect($this->_dbinfo);
            }
            else {
                $this->_connection= pg_pconnect($this->_dbinfo);
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

    function _setErrors($er) {
        if (!is_string($this->_errorCode))
            $errno= $this->_errorCode;
        if (!is_resource($this->_connection)) {
            $errno= 1;
            $errst= pg_last_error();
        } else {
            $errno= 1;
            $errst= pg_last_error($this->_connection);
        }
        $this->_errorCode= & $er;
        $this->_errorInfo= array (
            $this->_errorCode,
            $errno,
            $errst
        );
    }

    function _uquery(& $query) {
        if (!@ $this->_result= pg_query($this->_connection, $query)) {
            $this->_setErrors('SQLER');
            $this->_result= null;
        }
        return $this->_result;
    }
}

/**
 * A PostgreSQL PDOStatement class compatible with PHP 4.
 * 
 * @package xpdo
 */
class PDOStatement_pgsql extends PDOStatement {
    function PDOStatement_pgsql($queryString, & $connection, & $dbinfo) {
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
            $result= pg_num_fields($this->_result);
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
                    $v= $this->quote($v);
                    array_push($tempr, $v);
                } else {
                    $parse= create_function('$d,$v,$t', 'return $d->quote($v, $t);');
                    $queryString= preg_replace("/(\?)/e", '$parse($this,$array[$k],$type);', $queryString, 1);
                }
            }
            if (isset ($tempf))
                $queryString= str_replace($tempf, $tempr, $queryString);
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
                    $result= pg_fetch_row($this->_result);
                    break;
                case PDO_FETCH_ASSOC :
                    $result= pg_fetch_assoc($this->_result);
                    break;
                case PDO_FETCH_OBJ :
                    $result= pg_fetch_object($this->_result);
                    break;
                case PDO_FETCH_BOTH :
                default :
                    $result= pg_fetch_array($this->_result);
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
                    while ($r= @ pg_fetch_row($this->_result))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_COLUMN :
                    while ($r= @ pg_fetch_row($this->_result))
                        array_push($result, $r[$column_index]);
                    break;
                case PDO_FETCH_ASSOC :
                    while ($r= @ pg_fetch_assoc($this->_result))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_OBJ :
                    while ($r= @ pg_fetch_object($this->_result))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_BOTH :
                default :
                    while ($r= @ pg_fetch_array($this->_result))
                        array_push($result, $r);
                    break;
            }
        }
        $this->_result= null;
        return $result;
    }

    function fetchColumn($column_number= 0) {
        $result= null;
        if (!is_null($this->_result)) {
            $result= @ pg_fetch_row($this->_result);
            if ($result)
                $result= $result[$column_number];
            else
                $this->_result= null;
        }
        return $result;
    }

    function rowCount() {
        $result= 0;
        if (!is_null($this->_result))
            $result= @ pg_affected_rows($this->_result);
        return $result;
    }

    function getAttribute($attribute) {
        $result= false;
        switch ($attribute) {
            case PDO_ATTR_SERVER_INFO :
                $result= pg_parameter_status($this->_connection, 'server_encoding');
                break;
            case PDO_ATTR_SERVER_VERSION :
                $result= pg_parameter_status($this->_connection, 'server_version');
                break;
            case PDO_ATTR_CLIENT_VERSION :
                $result= pg_parameter_status($this->_connection, 'server_version');
                $result .= ' ' . pg_parameter_status($this->_connection, 'client_encoding');
                break;
            case PDO_ATTR_PERSISTENT :
                $result= $this->_persistent;
                break;
            case PDO_ATTR_DRIVER_NAME :
                $result= 'pgsql';
                break;
        }
        return $result;
    }

    function setAttribute($attribute, $value) {
        $result= false;
        if ($attribute === PDO_ATTR_PERSISTENT && $value != $this->_persistent) {
            $result= true;
            $this->_persistent= (boolean) $value;
            pg_close($this->_connection);
            if ($this->_persistent === true) {
                $this->_connection= pg_connect($this->_dbinfo);
            }
            else {
                $this->_connection= pg_pconnect($this->_dbinfo);
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

    function _setErrors($er) {
        if (!is_string($this->_errorCode))
            $errno= $this->_errorCode;
        if (!is_resource($this->_connection)) {
            $errno= 1;
            $errst= pg_last_error();
        } else {
            $errno= 1;
            $errst= pg_last_error($this->_connection);
        }
        $this->_errorCode= & $er;
        $this->_errorInfo= array (
            $this->_errorCode,
            $errno,
            $errst
        );
    }

    function _uquery(& $query) {
        if (!@ $query= pg_query($this->_connection, $query)) {
            $this->_setErrors('SQLER');
            $query= null;
        }
        return $query;
    }

    function quote($string, $parameter_type= PDO_PARAM_STR) {
        $string= pg_escape_string();
        switch ($parameter_type) {
            case PDO_PARAM_NULL:
                break;
            case PDO_PARAM_INT:
                break;
            default:
                $string= "'" . $string . "'";
        }
        return $string;
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