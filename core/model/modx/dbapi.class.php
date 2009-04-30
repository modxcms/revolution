<?php
/**
 * Legacy Database API class for MySQL.
 *
 * @deprecated MODx Revolution - Use modX and xPDO functions instead in order to
 * take advantage of support for any database platform with a PDO driver, as
 * well as to take advantage of xPDO advanced caching features.
 * @author Raymond Irving (June 2005)
 * @author Jason Coward <modx@opengeek.com>
 * @package modx
 */
class DBAPI {
    var $xpdo;
    var $conn;
    var $config;
    var $isConnected;
    var $affectedRows= false;

    /**
     * @name:  DBAPI
     */
    function DBAPI($host= '', $dbase= '', $uid= '', $pwd= '', $pre= '', $dbtype= '') {
        $this->__construct($host, $dbase, $uid, $pwd, $pre);
    }
    function __construct($host= '', $dbase= '', $uid= '', $pwd= '', $pre= '', $dbtype= '') {
        if (!$host || !$dbase || !$uid || !$pwd) {
            global $modx;
            if (is_object($modx)) {
                $this->xpdo= & $modx;
            }
        } else {
            if (!$dbtype) {
                $dbtype= 'mysql';
            }
            $this->xpdo= new xPDO(
                $dbtype . ':host=' . $host . ';dbname=' . str_replace('`', '', $dbase),
                $uid,
                $pwd,
                $pre
            );
        }
        if (is_object($this->xpdo) && is_a($this->xpdo, 'xPDO')) {
            $this->config['host']= $this->xpdo->config['host'];
            $this->config['dbase']= $this->xpdo->config['dbname'];
            $this->config['user']= $this->xpdo->config['username'];
            $this->config['pass']= $this->xpdo->config['password'];
            $this->config['table_prefix']= $this->xpdo->config['table_prefix'];
        }
    }

    /**
     * @name:  initDataTypes
     * @desc:  called in the constructor to set up arrays containing the types
     *         of database fields that can be used with specific PHP types
     */
    function initDataTypes() {
        $this->dataTypes['numeric']= array (
            'INT',
            'INTEGER',
            'TINYINT',
            'BOOLEAN',
            'DECIMAL',
            'DEC',
            'NUMERIC',
            'FLOAT',
            'DOUBLE PRECISION',
            'REAL',
            'SMALLINT',
            'MEDIUMINT',
            'BIGINT',
            'BIT'
        );
        $this->dataTypes['string']= array (
            'CHAR',
            'VARCHAR',
            'BINARY',
            'VARBINARY',
            'TINYBLOB',
            'BLOB',
            'MEDIUMBLOB',
            'LONGBLOB',
            'TINYTEXT',
            'TEXT',
            'MEDIUMTEXT',
            'LONGTEXT',
            'ENUM',
            'SET'
        );
        $this->dataTypes['date']= array (
            'DATE',
            'DATETIME',
            'TIMESTAMP',
            'TIME',
            'YEAR'
        );
    }

    /**
     * @name:  connect
     */
    function connect($host= '', $dbase= '', $uid= '', $pwd= '', $persist= 0) {
        $tstart= $this->xpdo->getMicroTime();
        if (!$this->conn= $this->xpdo->connect(array (PDO_ATTR_PERSISTENT => $persist ? true : false))) {
            $modx->messageQuit("Failed to create the database connection!");
            exit;
        } else {
            $tend= $this->xpdo->getMicroTime();
            $totaltime= $tend - $tstart;
            $this->isConnected= true;
            $this->xpdo->queryTime= $this->xpdo->queryTime + $totaltime;
        }
    }

    /**
     * @name:  disconnect
     */
    function disconnect() {
        return true;
    }

    function escape($s) {
        $s= $this->xpdo->quote($s, PDO_PARAM_STR);
        $s= substr($s, 1, strlen($s) - 2);
        return $s;
    }

    /**
     * @name:  query
     * @desc:  Mainly for internal use.
     * Developers should use select, update, insert, delete where possible
     */
    function query($sql) {
        if (empty ($this->conn) || !is_a('PDO', $this->conn)) {
            $this->connect();
        }
        $tstart= $this->xpdo->getMicroTime();
        $result= $this->xpdo->query($sql);
        if (!$result) {
            $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, 'Execution of a query to the database failed: ' . $sql . "\n" . print_r($this->xpdo->errorInfo(), true) . "\n" . ($this->xpdo->getDebug() === true ? print_r(debug_backtrace(), true) : ''));
        }
        $this->affectedRows= $this->getRecordCount($result);
        $tend= $this->xpdo->getMicroTime();
        $totaltime= $tend - $tstart;
        $this->xpdo->queryTime= $this->xpdo->queryTime + $totaltime;
        $this->xpdo->executedQueries= $this->xpdo->executedQueries + 1;
        return $result;
    }

    /**
     * @name:  delete
     *
     */
    function delete($from, $where= "",$fields='') {
        if (!$from)
            return false;
        else {
            $table= $from;
            $where= ($where != "") ? "WHERE $where" : "";
            return $this->exec("DELETE $fields FROM $table $where");
        }
    }

    /**
     * @name:  select
     *
     */
    function select($fields= "*", $from= "", $where= "", $orderby= "", $limit= "") {
        if (!$from)
            return false;
        else {
            $table= $from;
            $where= ($where != "") ? "WHERE $where" : "";
            $orderby= ($orderby != "") ? "ORDER BY $orderby " : "";
            $limit= ($limit != "") ? "LIMIT $limit" : "";
            return $this->query("SELECT $fields FROM $table $where $orderby $limit");
        }
    }

    /**
     * @name:  update
     *
     */
    function update($fields, $table, $where= "") {
        if (!$table)
            return false;
        else {
            if (!is_array($fields))
                $flds= $fields;
            else {
                $flds= '';
                foreach ($fields as $key => $value) {
                    if (!empty ($flds))
                        $flds .= ",";
                    $flds .= $key . "=";
                    $flds .= "'" . $value . "'";
                }
            }
            $where= ($where != "") ? "WHERE $where" : "";
            return $this->exec("UPDATE $table SET $flds $where");
        }
    }

    /**
     * @name:  insert
     * @desc:  returns either last id inserted or the result from the query
     */
    function insert($fields, $intotable, $fromfields= "*", $fromtable= "", $where= "", $limit= "") {
        if (!$intotable)
            return false;
        else {
            if (!is_array($fields))
                $flds= $fields;
            else {
                $keys= array_keys($fields);
                $values= array_values($fields);
                $flds= "(" . implode(",", $keys) . ") " .
                 (!$fromtable && $values ? "VALUES('" . implode("','", $values) . "')" : "");
                if ($fromtable) {
                    $where= ($where != "") ? "WHERE $where" : "";
                    $limit= ($limit != "") ? "LIMIT $limit" : "";
                    $sql= "SELECT $fromfields FROM $fromtable $where $limit";
                }
            }
            $rt= $this->exec("INSERT INTO $intotable $flds $sql");
            $lid= $this->getInsertId();
            return $lid ? $lid : $rt;
        }
    }

    function exec($sql) {
        if (empty ($this->conn) || !is_a('xPDO', $this->conn)) {
            $this->connect();
        }
        $tstart= $this->xpdo->getMicroTime();
        $result= $this->xpdo->exec($sql);
        if ($result === false) {
            $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, 'Execution of a query to the database failed: ' . $sql . "\n" . print_r($this->xpdo->errorInfo(), true));
        }
        $this->affectedRows= $result;
        $tend= $this->xpdo->getMicroTime();
        $totaltime= $tend - $tstart;
        $this->xpdo->queryTime= $this->xpdo->queryTime + $totaltime;
        $this->xpdo->executedQueries= $this->xpdo->executedQueries + 1;
        return $result;
    }

    /**
     * @name:  getInsertId
     *
     */
    function getInsertId($conn= null) {
        return $this->xpdo->lastInsertId();
    }

    /**
     * @name:  getAffectedRows
     *
     */
    function getAffectedRows($conn= null) {
        return $this->affectedRows;
    }

    /**
     * @name:  getLastError
     *
     */
    function getLastError() {
        return $this->xpdo->errorInfo();
    }

    /**
     * @name:  getRecordCount
     *
     */
    function getRecordCount($ds) {
        $count= false;
        if (is_object($ds)) {
            return $ds->rowCount();
        }
        elseif (is_array($ds)) {
            return count($ds);
        }
        elseif (is_resource($ds)) {
            return mysql_num_rows($ds);
        }
        return $count;
    }

    /**
     * @name:  getRow
     * @desc:  returns an array of column values
     * @param: $dsq - dataset
     *
     */
    function getRow($ds, $mode= 'assoc') {
        if (is_resource($ds)) {
            if ($ds) {
                if ($mode == 'assoc') {
                    return mysql_fetch_assoc($ds);
                }
                elseif ($mode == 'num') {
                    return mysql_fetch_row($ds);
                }
                elseif ($mode == 'both') {
                    return mysql_fetch_array($ds, MYSQL_BOTH);
                } else {
                    global $modx;
                    $modx->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
                }
            }
        } elseif (is_object($ds)) {
            switch ($mode) {
            	case 'num':
            		$fetchMode= PDO_FETCH_NUM;
            		break;
            	case 'both':
            		$fetchMode= PDO_FETCH_BOTH;
            		break;
            	default:
                    $fetchMode= PDO_FETCH_ASSOC;
            		break;
            }
            return $ds->fetch($fetchMode);
        } elseif (is_array($ds)) {
            switch ($mode) {
                case 'num':
                    if ($row= next($ds)) {
                        return array_values($row);
                    }
                    break;
                case 'both':
                    if ($row= next($ds)) {
                        return array_values($row) + $row;
                    }
                    break;
                default:
                    if ($row= next($ds)) {
                        return $row;
                    }
                    break;
            }
            return false;
        }
        return false;
    }

    /**
     * @name:  getColumn
     * @desc:  returns an array of the values found on colun $name
     * @param: $dsq - dataset or query string
     */
    function getColumn($name, $dsq) {
        if (!is_resource($dsq))
            $dsq= $this->query($dsq);
        if ($dsq) {
            $col= array ();
            while ($row= $this->getRow($dsq)) {
                $col[]= $row[$name];
            }
            return $col;
        }
    }

    /**
     * @name:  getColumnNames
     * @desc:  returns an array containing the column $name
     * @param: $dsq - dataset or query string
     */
    function getColumnNames($dsq) {
        if (!is_resource($dsq))
            $dsq= $this->query($dsq);
        if ($dsq) {
            $names= array ();
            $limit= $this->getRecordCount($dsq);
            for ($i= 0; $i < $limit; $i++) {
                $names[]= mysql_field_name($dsq, $i);
            }
            return $names;
        }
    }

    /**
     * @name:  getValue
     * @desc:  returns the value from the first column in the set
     * @param: $dsq - dataset or query string
     */
    function getValue($dsq) {
        if (!is_resource($dsq))
            $dsq= $this->query($dsq);
        if ($dsq) {
            $r= $this->getRow($dsq, "num");
            return $r[0];
        }
    }

    /**
     * @name:  getXML
     * @desc:  returns an XML formay of the dataset $ds
     */
    function getXML($dsq) {
        if (!is_resource($dsq))
            $dsq= $this->query($dsq);
        $xmldata= "<xml>\r\n<recordset>\r\n";
        while ($row= $this->getRow($dsq, "both")) {
            $xmldata .= "<item>\r\n";
            for ($j= 0; $line= each($row); $j++) {
                if ($j % 2) {
                    $xmldata .= "<$line[0]>$line[1]</$line[0]>\r\n";
                }
            }
            $xmldata .= "</item>\r\n";
        }
        $xmldata .= "</recordset>\r\n</xml>";
        return $xmldata;
    }

    /**
     * @name:  getTableMetaData
     * @desc:  returns an array of MySQL structure detail for each column of a
     *         table
     * @param: $table: the full name of the database table
     */
    function getTableMetaData($table) {
        $metadata= false;
        if (!empty ($table)) {
            $sql= "SHOW FIELDS FROM $table";
            if ($ds= $this->query($sql)) {
                while ($row= $this->getRow($ds)) {
                    $fieldName= $row['Field'];
                    $metadata[$fieldName]= $row;
                }
            }
        }
        return $metadata;
    }

    /**
     * @name:  prepareDate
     * @desc:  prepares a date in the proper format for specific database types
     *         given a UNIX timestamp
     * @param: $timestamp: a UNIX timestamp
     * @param: $fieldType: the type of field to format the date for
     *         (in MySQL, you have DATE, TIME, YEAR, and DATETIME)
     */
    function prepareDate($timestamp, $fieldType= 'DATETIME') {
        $date= '';
        if (!$timestamp === false && $timestamp > 0) {
            switch ($fieldType) {
                case 'DATE' :
                    $date= date('Y-m-d', $timestamp);
                    break;
                case 'TIME' :
                    $date= date('H:i:s', $timestamp);
                    break;
                case 'YEAR' :
                    $date= date('Y', $timestamp);
                    break;
                default :
                    $date= date('Y-m-d H:i:s', $timestamp);
                    break;
            }
        }
        return $date;
    }

    /**
     * @name:  getHTMLGrid
     * @param: $params: Data grid parameters
     *         columnHeaderClass
     *         tableClass
     *         itemClass
     *         altItemClass
     *         columnHeaderStyle
     *         tableStyle
     *         itemStyle
     *         altItemStyle
     *         columns
     *         fields
     *         colWidths
     *         colAligns
     *         colColors
     *         colTypes
     *         cellPadding
     *         cellSpacing
     *         header
     *         footer
     *         pageSize
     *         pagerLocation
     *         pagerClass
     *         pagerStyle
     *
     */
    function getHTMLGrid($dsq, $params) {
        if (!is_resource($dsq))
            $dsq= $this->query($dsq);
        if ($dsq) {
            $dsq= $dsq->fetchAll(PDO_FETCH_ASSOC);
            include_once MODX_BASE_PATH . "/manager/includes/controls/datagrid.class.php";
            $grd= new DataGrid('', $dsq);

            $grd->noRecordMsg= $params['noRecordMsg'];

            $grd->columnHeaderClass= $params['columnHeaderClass'];
            $grd->tableClass= $params['tableClass'];
            $grd->itemClass= $params['itemClass'];
            $grd->altItemClass= $params['altItemClass'];

            $grd->columnHeaderStyle= $params['columnHeaderStyle'];
            $grd->tableStyle= $params['tableStyle'];
            $grd->itemStyle= $params['itemStyle'];
            $grd->altItemStyle= $params['altItemStyle'];

            $grd->columns= $params['columns'];
            $grd->fields= $params['fields'];
            $grd->colWidths= $params['colWidths'];
            $grd->colAligns= $params['colAligns'];
            $grd->colColors= $params['colColors'];
            $grd->colTypes= $params['colTypes'];

            $grd->cellPadding= $params['cellPadding'];
            $grd->cellSpacing= $params['cellSpacing'];
            $grd->header= $params['header'];
            $grd->footer= $params['footer'];
            $grd->pageSize= $params['pageSize'];
            $grd->pagerLocation= $params['pagerLocation'];
            $grd->pagerClass= $params['pagerClass'];
            $grd->pagerStyle= $params['pagerStyle'];
            return $grd->render();
        }
    }

    /**
    * @name:  makeArray
    * @desc:  turns a recordset into a multidimensional array
    * @return: an array of row arrays from recordset, or empty array
    *          if the recordset was empty, returns false if no recordset
    *          was passed
    * @param: $rs Recordset to be packaged into an array
    */
    function makeArray($rs= '') {
        if (!$rs)
            return false;
        $rsArray= array ();
        $qty= $this->getRecordCount($rs);
        for ($i= 0; $i < $qty; $i++) {
            array_push($rsArray, $this->getRow($rs));
        }
        return $rsArray;
    }
}
