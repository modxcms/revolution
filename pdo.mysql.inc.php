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
 * Contains the classes required to support MySQL PDO when native PDO is not
 * available to the platform.
 * 
 * @package xpdo
 */

/**
 * A MySQL PDO implementation for PHP 4 and 5.0.x.
 * 
 * @package xpdo
 */
class PDO_mysql {
    var $_connection;
    var $_dbinfo;
    var $_persistent= false;
    var $_errorCode= '';
    var $_errorInfo= array (
        ''
    );

    function PDO_mysql(& $host, & $db, & $user, & $pass) {
        if (!$this->_connection= @ mysql_connect($host, $user, $pass)) {
            $this->_setErrors('DBCON');
        } else {
            if (!@ mysql_select_db($db, $this->_connection)) {
                $this->_setErrors('DBER');
            } else {
                $this->_dbinfo= array (
                    $host,
                    $user,
                    $pass,
                    $db
                );
            }
        }
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
            $result= mysql_affected_rows($this->_connection);
        if (is_null($result))
            $result= false;
        return $result;
    }

    function lastInsertId() {
        return mysql_insert_id($this->_connection);
    }

    function prepare($statement, $driver_options= array ()) {
        return new PDOStatement_mysql($statement, $this->_connection, $this->_dbinfo);
    }

    function query($statement) {
        $args= func_get_args();
        $result= false;
        if ($stmt= new PDOStatement_mysql($statement, $this->_connection, $this->_dbinfo)) {
            if (count($args) > 1) {
                $stmt->setFetchMode($args[1]);
            }
            $stmt->execute();
            $result= & $stmt;
        }
        return $result;
    }

    function quote($string, $parameter_type= PDO_PARAM_STR) {
        if (function_exists('mysql_real_escape_string') && $this->_connection) {
            $string= mysql_real_escape_string($string, $this->_connection);
        } else {
            $string= mysql_escape_string($string);
        }
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
                $result= mysql_get_host_info($this->_connection);
                break;
            case PDO_ATTR_SERVER_VERSION :
                $result= mysql_get_server_info($this->_connection);
                break;
            case PDO_ATTR_CLIENT_VERSION :
                $result= mysql_get_client_info();
                break;
            case PDO_ATTR_PERSISTENT :
                $result= $this->_persistent;
                break;
            case PDO_ATTR_DRIVER_NAME :
                $result= 'mysql';
                break;
        }
        return $result;
    }

    function setAttribute($attribute, $value) {
        $result= false;
        if ($attribute === PDO_ATTR_PERSISTENT && $value != $this->_persistent) {
            $result= true;
            $this->_persistent= (boolean) $value;
            mysql_close($this->_connection);
            if ($this->_persistent === true) {
                $this->_connection= mysql_pconnect($this->_dbinfo[0], $this->_dbinfo[1], $this->_dbinfo[2]);
            }
            else {
                $this->_connection= mysql_connect($this->_dbinfo[0], $this->_dbinfo[1], $this->_dbinfo[2]);
            }
            mysql_select_db($this->_dbinfo[3], $this->_connection);
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
        if (!is_resource($this->_connection)) {
            $errno= mysql_errno();
            $errst= mysql_error();
        } else {
            $errno= mysql_errno($this->_connection);
            $errst= mysql_error($this->_connection);
        }
        $this->_errorCode= & $er;
        $this->_errorInfo= array (
            $this->_errorCode,
            $errno,
            $errst
        );
    }

    function _uquery(& $query) {
        if (!$query= @ mysql_query($query, $this->_connection)) {
            $this->_setErrors('SQLER');
            $query= null;
        }
        return $query;
    }
}

/**
 * A MySQL PDOStatement class compatible with PHP 4.
 * 
 * @package xpdo
 */
class PDOStatement_mysql extends PDOStatement {
    function PDOStatement_mysql($queryString, & $connection, & $dbinfo) {
        $this->__construct($queryString, $connection, $dbinfo);
    }
    function __construct($queryString, & $connection, & $dbinfo) {
        parent :: __construct($queryString, $connection, $dbinfo);
    }

    function columnCount() {
        $result= 0;
        if (!is_null($this->_result))
            $result= mysql_num_fields($this->_result);
        return $result;
    }

    function fetch($mode= PDO_FETCH_BOTH, $cursor= null, $offset= null) {
        if (func_num_args() == 0)
            $mode= & $this->_fetchmode;
        $result= false;
        if (!is_null($this->_result)) {
            switch ($mode) {
                case PDO_FETCH_NUM :
                    $result= @ mysql_fetch_row($this->_result);
                    break;
                case PDO_FETCH_ASSOC :
                    $result= @ mysql_fetch_assoc($this->_result);
                    break;
                case PDO_FETCH_OBJ :
                    $result= @ mysql_fetch_object($this->_result);
                    break;
                case PDO_FETCH_BOTH :
                default :
                    $result= @ mysql_fetch_array($this->_result);
                    break;
            }
        }
        if (!$result)
            $this->_result= null;
        return $result;
    }

    function fetchAll($mode= PDO_FETCH_BOTH, $column_index= 0) {
        if (func_num_args() == 0)
            $mode= & $this->_fetchmode;
        $result= array ();
        if (!is_null($this->_result)) {
            switch ($mode) {
                case PDO_FETCH_NUM :
                    while ($r= @ mysql_fetch_row($this->_result))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_ASSOC :
                    while ($r= @ mysql_fetch_assoc($this->_result))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_OBJ :
                    while ($r= @ mysql_fetch_object($this->_result))
                        array_push($result, $r);
                    break;
                case PDO_FETCH_COLUMN :
                    while ($r= @ mysql_fetch_row($this->_result))
                        array_push($result, $r[$column_index]);
                    break;
                case PDO_FETCH_BOTH :
                default :
                    while ($r= @ mysql_fetch_array($this->_result))
                        array_push($result, $r);
                    break;
            }
        }
        $this->_result= null;
        return $result;
    }

    function fetchColumn($column_number= 0) {
        $result= false;
        if (!is_null($this->_result)) {
            $result= @ mysql_fetch_row($this->_result);
            if ($result)
                $result= $result[$column_number];
            else
                $this->_result= false;
        }
        return $result;
    }

    function getAttribute($attribute) {
        $result= false;
        switch ($attribute) {
            case PDO_ATTR_SERVER_INFO :
                $result= mysql_get_host_info($this->_connection);
                break;
            case PDO_ATTR_SERVER_VERSION :
                $result= mysql_get_server_info($this->_connection);
                break;
            case PDO_ATTR_CLIENT_VERSION :
                $result= mysql_get_client_info();
                break;
            case PDO_ATTR_PERSISTENT :
                $result= $this->_persistent;
                break;
            case PDO_ATTR_DRIVER_NAME :
                $result= '';
                break;
        }
        return $result;
    }

    function quote($string, $parameter_type= PDO_PARAM_STR) {
        if (function_exists('mysql_real_escape_string') && $this->_connection) {
            $string= mysql_real_escape_string($string, $this->_connection);
        } else {
            $string= mysql_escape_string($string);
        }
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

    function rowCount() {
        return mysql_affected_rows($this->_connection);
    }

    function setAttribute($attribute, $value) {
        $result= false;
        if ($attribute === PDO_ATTR_PERSISTENT && $value != $this->_persistent) {
            $result= true;
            $this->_persistent= (boolean) $value;
            mysql_close($this->_connection);
            if ($this->_persistent === true) {
                $this->_connection= mysql_pconnect($this->_dbinfo[0], $this->_dbinfo[1], $this->_dbinfo[2]);
            }
            else {
                $this->_connection= mysql_connect($this->_dbinfo[0], $this->_dbinfo[1], $this->_dbinfo[2]);
            }
            mysql_select_db($this->_dbinfo[3], $this->_connection);
        }
        return $result;
    }

    function _uquery(& $query) {
        if (!@ $query= mysql_query($query, $this->_connection)) {
            $this->_setErrors('SQLER');
            $query= null;
        }
        return $query;
    }

    function _setErrors($er) {
        if (!is_resource($this->_connection)) {
            $errno= mysql_errno();
            $errst= mysql_error();
        } else {
            $errno= mysql_errno($this->_connection);
            $errst= mysql_error($this->_connection);
        }
        $this->_errorCode= & $er;
        $this->_errorInfo= array (
            $this->_errorCode,
            $errno,
            $errst
        );
        $this->_result= null;
    }
}
?>
