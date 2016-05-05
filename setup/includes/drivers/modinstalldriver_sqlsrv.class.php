<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/modinstalldriver.class.php');
/**
 * Provides query abstraction for setup using the sqlsrv native database driver.
 *
 * @package setup
 * @subpackage drivers
 */
class modInstallDriver_sqlsrv extends modInstallDriver {
    /**
     * Check for sqlsrv extension
     * {@inheritDoc}
     */
    public function verifyExtension() {
        return extension_loaded('sqlsrv');
    }

    /**
     * Check for sqlsrv_pdo extension
     * {@inheritDoc}
     */
    public function verifyPDOExtension() {
        return extension_loaded('pdo_sqlsrv');
    }

    /**
     * SQL Server syntax for default collation query
     * {@inheritDoc}
     */
    public function getCollation() {
        $collation = 'Latin1_General_100_BIN2';
//        $stmt = $this->xpdo->query("SELECT CAST(SERVERPROPERTY('Collation') AS varchar(128))");
//        if ($stmt && $stmt instanceof PDOStatement) {
//            $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
//            $collation = reset($rows);
//        }
        return $collation;
    }

    /**
     * SQL Server syntax for collation listing
     * {@inheritDoc}
     */
    public function getCollations($collation = '') {
        $collations = null;
        $stmt = $this->xpdo->query("SELECT * FROM sys.fn_helpcollations()");
        if ($stmt && $stmt instanceof PDOStatement) {
            $collations = array();
            if (empty($collation)) $collation = $this->getCollation();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $col = array();
                $col['selected'] = ($row['name']==$collation ? ' selected="selected"' : '');
                $col['value'] = $row['name'];
                $col['name'] = $row['name'];
                $collations[$row['name']] = $col;
            }
            ksort($collations);
        }
        return $collations;
    }

    public function getCharset($collation = '') {
        $charset = 'Latin1';
        if (empty($collation)) {
            $collation = $this->getCollation();
        }
        $pos = strpos($collation, '_');
        if ($pos > 0) {
            $charset = substr($collation, 0, $pos);
        }
        return $charset;
    }

    /**
     * SQL Server syntax for charset listing
     * {@inheritDoc}
     */
    public function getCharsets($charset = '') {
        if (empty($charset)) {
            $charset = $this->getCharset();
        }
        return array($charset => array(
            'selected' => ' selected="selected"',
            'value' => $charset,
            'name' => $charset
        ));
    }

    /**
     * SQL Server syntax for table prefix check
     * {@inheritDoc}
     */
    public function testTablePrefix($database,$prefix) {
        return 'SELECT COUNT('.$this->xpdo->escape('id').') AS '.$this->xpdo->escape('ct').' FROM '.$this->xpdo->escape($prefix.'site_content');
    }

    /**
     * SQL Server syntax for table truncation
     * {@inheritDoc}
     */
    public function truncate($table) {
        return 'TRUNCATE '.$this->xpdo->escape($table);
    }

    /**
     * SQL Server check for server version
     *
     * @TODO Get this to actually check the server version.
     *
     * {@inheritDoc}
     */
    public function verifyServerVersion() {
        return array('result' => 'success','message' => $this->install->lexicon('sqlsrv_version_success',array('version' => '')));

        $handler = @sqlsrv_connect($this->install->settings->get('database_server'),$this->install->settings->get('database_user'),$this->install->settings->get('database_password'));
        $serverInfo = @sqlsrv_server_info($handler);
        $sqlsrvVersion = $serverInfo['SQLServerVersion'];
        $sqlsrvVersion = $this->_sanitizeVersion($sqlsrvVersion);
        if (empty($sqlsrvVersion)) {
            return array('result' => 'warning', 'message' => $this->install->lexicon('sqlsrv_version_server_nf'),'version' => $sqlsrvVersion);
        }

        $sqlsrv_ver_comp = version_compare($sqlsrvVersion,'10.50.0','>=');

        if (!$sqlsrv_ver_comp) { /* ancient driver warning */
            return array('result' => 'failure','message' => $this->install->lexicon('sqlsrv_version_fail',array('version' => $sqlsrvVersion)),'version' => $sqlsrvVersion);
        } else {
            return array('result' => 'success','message' => $this->install->lexicon('sqlsrv_version_success',array('version' => $sqlsrvVersion)),'version' => $sqlsrvVersion);
        }
    }

    /**
     * SQL Server check for client version
     *
     * @TODO Get this to actually check the client version.
     *
     * {@inheritDoc}
     */
    public function verifyClientVersion() {
        return array('result' => 'success', 'message' => $this->install->lexicon('sqlsrv_version_client_success',array('version' => '')));

        $clientInfo = @sqlsrv_client_info();
        $sqlsrvVersion = $clientInfo['DriverVer'];
        $sqlsrvVersion = $this->_sanitizeVersion($sqlsrvVersion);
        if (empty($sqlsrvVersion)) {
            return array('result' => 'warning','message' => $this->install->lexicon('sqlsrv_version_client_nf'),'version' => $sqlsrvVersion);
        }

        $sqlsrv_ver_comp = version_compare($sqlsrvVersion,'10.50.0','>=');
        if (!$sqlsrv_ver_comp) {
            return array('result' => 'warning','message' => $this->install->lexicon('sqlsrv_version_client_old',array('version' => $sqlsrvVersion)),'version' => $sqlsrvVersion);
        } else {
            return array('result' => 'success','message' => $this->install->lexicon('sqlsrv_version_success',array('version' => $sqlsrvVersion)),'version' => $sqlsrvVersion);
        }
    }

    /**
     * SQL Server syntax to add an index
     * {@inheritDoc}
     */
    public function addIndex($table,$name,$column) {
        return 'ALTER TABLE '.$this->xpdo->escape($table).' ADD INDEX '.$this->xpdo->escape($name).' ('.$this->xpdo->escape($column).')"';
    }

    /**
     * SQL Server syntax to drop an index
     * {@inheritDoc}
     */
    public function dropIndex($table,$index) {
        return 'ALTER TABLE '.$this->xpdo->escape($table).' DROP INDEX '.$this->xpdo->escape($index);
    }


    /**
     * Cleans a sqlsrv version string that often has extra junk in certain distros
     *
     * @param string $sqlsrvVersion The version note to sanitize
     * @return string The sanitized version
     */
    protected function _sanitizeVersion($sqlsrvVersion) {
        return $sqlsrvVersion;
    }
}
