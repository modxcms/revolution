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
    public $xpdo;
    public $conn;
    public $config;
    public $isConnected;
    public $affectedRows= false;

    /**
     * DBAPI constructor
     */
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
        if (is_object($this->xpdo) && $this->xpdo instanceof xPDO) {
            $this->config['host']= $this->xpdo->config['host'];
            $this->config['dbase']= $this->xpdo->config['dbname'];
            $this->config['user']= $this->xpdo->config['username'];
            $this->config['pass']= $this->xpdo->config['password'];
            $this->config['table_prefix']= $this->xpdo->config['table_prefix'];
        }
    }

    /**
     * Called in the constructor to set up arrays containing the types of
     * database fields that can be used with specific PHP types
     *
     * @access public
     */
    public function initDataTypes() {
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
     * Connect to database.
     *
     * @access public
     */
    public function connect($host= '', $dbase= '', $uid= '', $pwd= '', $persist= 0) {
        $tstart= $this->xpdo->getMicroTime();
        if (!$this->conn= $this->xpdo->connect(array (PDO::ATTR_PERSISTENT => $persist ? true : false))) {
            $this->xpdo->log(xPDO::LOG_LEVEL_FATAL,"Failed to create the database connection!");
            exit;
        } else {
            $tend= $this->xpdo->getMicroTime();
            $totaltime= $tend - $tstart;
            $this->isConnected= true;
            $this->xpdo->queryTime= $this->xpdo->queryTime + $totaltime;
        }
    }

    /**
     * Disconnect from the DB
     *
     * @access public
     */
    public function disconnect() {
        return true;
    }

    /**
     * Escape field data
     *
     * @access public
     * @param string $s
     * @return string
     */
    public function escape($s) {
        $s= $this->xpdo->quote($s, PDO::PARAM_STR);
        $s= substr($s, 1, strlen($s) - 2);
        return $s;
    }

    /**
     * Mainly for internal use. Developers should use select, update, insert,
     * delete where possible
     *
     * @access public
     */
    public function query($sql) {
        if (empty ($this->conn) || !($this->conn instanceof PDO)) {
            $this->connect();
        }
        $tstart= $this->xpdo->getMicroTime();
        $result= $this->xpdo->query($sql);
        if (!$result) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Execution of a query to the database failed: ' . $sql . "\n" . print_r($this->xpdo->errorInfo(), true) . "\n" . ($this->xpdo->getDebug() === true ? print_r(debug_backtrace(), true) : ''));
        }
        $this->affectedRows= $this->getRecordCount($result);
        $tend= $this->xpdo->getMicroTime();
        $totaltime= $tend - $tstart;
        $this->xpdo->queryTime= $this->xpdo->queryTime + $totaltime;
        $this->xpdo->executedQueries= $this->xpdo->executedQueries + 1;
        return $result;
    }

    /**
     * Delete a record from the DB
     *
     * @access public
     * @param string $from
     * @param string $where
     * @param string $fields
     * @return boolean
     */
    public function delete($from, $where= "",$fields='') {
        if (!$from)
            return false;
        else {
            $table= $from;
            $where= ($where != "") ? "WHERE $where" : "";
            return $this->exec("DELETE $fields FROM $table $where");
        }
    }

    /**
     * Select a record from the DB
     *
     * @access public
     * @param string $fields
     * @param string $from
     * @param string $where
     * @param string $orderby
     * @param string $limit
     * @return mixed
     */
    public function select($fields= "*", $from= "", $where= "", $orderby= "", $limit= "") {
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
     * Update a record in the DB
     *
     * @access public
     * @param mixed $fields
     * @param string $table
     * @param string $where
     * @return mixed
     */
    public function update($fields, $table, $where= "") {
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
     * Insert a record into the DB.
     *
     * @access public
     * @param mixed $fields
     * @param string $intotable
     * @param string $fromfields
     * @param string $fromtable
     * @param string $where
     * @param string $limit
     * @return mixed Either last id inserted or the result from the query
     */
    public function insert($fields, $intotable, $fromfields= "*", $fromtable= "", $where= "", $limit= "") {
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

    /**
     * Execute an SQL query
     *
     * @access public
     * @param string $sql
     * @return boolean Result of query.
     */
    public function exec($sql) {
        if (empty ($this->conn) || !($this->conn instanceof PDO)) {
            $this->connect();
        }
        $tstart= $this->xpdo->getMicroTime();
        $result= $this->xpdo->exec($sql);
        if ($result === false) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Execution of a query to the database failed: ' . $sql . "\n" . print_r($this->xpdo->errorInfo(), true));
        }
        $this->affectedRows= $result;
        $tend= $this->xpdo->getMicroTime();
        $totaltime= $tend - $tstart;
        $this->xpdo->queryTime= $this->xpdo->queryTime + $totaltime;
        $this->xpdo->executedQueries= $this->xpdo->executedQueries + 1;
        return $result;
    }

    /**
     * Get the last INSERTed primary key
     *
     * @access public
     * @param mixed $conn
     * @return mixed
     */
    public function getInsertId($conn= null) {
        return $this->xpdo->lastInsertId();
    }

    /**
     * Get affected rows by last query
     *
     * @access public
     * @param mixed $conn
     * @return integer
     */
    public function getAffectedRows($conn= null) {
        return $this->affectedRows;
    }

    /**
     * Get last error from DB
     *
     * @access public
     * @return string
     */
    public function getLastError() {
        return $this->xpdo->errorInfo();
    }

    /**
     * Get record count from last query
     *
     * @access public
     * @return integer
     */
    public function getRecordCount($ds) {
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
     * Gets a row of values from a result set
     *
     * @access public
     * @param mixed $ds
     * @param string $mode
     * @return array The row of column name - value pairs
     */
    public function getRow($ds, $mode= 'assoc') {
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
            		$fetchMode= PDO::FETCH_NUM;
            		break;
            	case 'both':
            		$fetchMode= PDO::FETCH_BOTH;
            		break;
            	default:
                    $fetchMode= PDO::FETCH_ASSOC;
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
     * Get an array of values for a column
     *
     * @access public
     * @param string $name
     * @param mixed $dsq
     * @return array
     */
    public function getColumn($name, $dsq) {
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
     * Returns an array of all the column names
     *
     * @access public
     * @param mixed $dsq
     * @return array
     */
    public function getColumnNames($dsq) {
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
     * Returns the value from the first column in the set
     *
     * @access public
     * @param mixed $dsq Dataset or query string
     * @return mixed
     */
    public function getValue($dsq) {
        if (!is_resource($dsq))
            $dsq= $this->query($dsq);
        if ($dsq) {
            $r= $this->getRow($dsq, "num");
            return $r[0];
        }
    }

    /**
     * Returns an XML format of the dataset $ds
     *
     * @access public
     * @param mixed $dsq The dataset or query string
     * @return string
     */
    public function getXML($dsq) {
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
     * Returns an array of MySQL structure detail for each column of a table
     *
     * @access public
     * @param string $table the full name of the database table
     * @return array
     */
    public function getTableMetaData($table) {
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
     * Prepares a date in the proper format for specific database types
     * given a UNIX timestamp
     *
     * @acecss public
     * @param $timestamp A UNIX timestamp
     * @param $fieldType The type of field to format the date for (in MySQL, you
     * have DATE, TIME, YEAR, and DATETIME)
     * @return string
     */
    public function prepareDate($timestamp, $fieldType= 'DATETIME') {
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
     * Get an HTML grid
     *
     * @access public
     * @param $params Data grid parameters         columnHeaderClass tableClass
     * itemClass         altItemClass columnHeaderStyle         tableStyle
     * itemStyle altItemStyle         columns         fields         colWidths
     * colAligns         colColors         colTypes         cellPadding
     * cellSpacing         header         footer         pageSize pagerLocation
     * pagerClass         pagerStyle
     * @return string
     */
    function getHTMLGrid($dsq, $params) {
        if (!is_resource($dsq))
            $dsq= $this->query($dsq);
        if ($dsq) {
            $dsq= $dsq->fetchAll(PDO::FETCH_ASSOC);
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
     * Turns a recordset into a multidimensional array
     *
     * @access public
     * @param $rs Recordset to be packaged into an array
     * @return An array of row arrays from recordset, or empty array if the
     * recordset was empty, returns false if no recordset was passed
     */
    public function makeArray($rs= '') {
        if (!$rs) return false;
        $rsArray= array ();
        $qty= $this->getRecordCount($rs);
        for ($i= 0; $i < $qty; $i++) {
            array_push($rsArray, $this->getRow($rs));
        }
        return $rsArray;
    }
}
