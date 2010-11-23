<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009, 2010 by the MODx Team.
 * All rights reserved.
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
        return "SELECT CAST(SERVERPROPERTY('Collation') AS varchar(128))";
    }

    /**
     * SQL Server syntax for collation listing
     * {@inheritDoc}
     */
    public function getCollations() {
        return "SELECT * FROM sys.fn_helpcollations()";
    }

    /**
     * SQL Server syntax for charset listing
     * {@inheritDoc}
     */
    public function getCharsets() {
        return '';
    }

    /**
     * SQL Server syntax for table prefix check
     * {@inheritDoc}
     */
    public function testTablePrefix($database,$prefix) {
        return 'SELECT COUNT('.$this->xpdo->escape('id').') AS '.$this->xpdo->escape('ct').' FROM '.$this->xpdo->escape($database).'.'.$this->xpdo->escape($prefix.'site_content');
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
     * {@inheritDoc}
     */
    public function verifyServerVersion() {
        return array('result' => 'success','message' => 'Your SQL Server version rocks');

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
     * {@inheritDoc}
     */
    public function verifyClientVersion() {
        return array('result' => 'success', 'message' => 'Your SQL Server client version rocks.');

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