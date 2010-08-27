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
 * Provides query abstraction for setup using the MySQL database
 *
 * @package setup
 * @subpackage drivers
 */
class modInstallDriver_mysql extends modInstallDriver {
    /**
     * MySQL check for mysql extension
     * {@inheritDoc}
     */
    public function verifyExtension() {
        return extension_loaded('mysql') && function_exists('mysql_connect');
    }

    /**
     * MySQL check for mysql_pdo extension
     * {@inheritDoc}
     */
    public function verifyPDOExtension() {
        return extension_loaded('pdo_mysql');
    }

    /**
     * MySQL syntax for default collation query
     * {@inheritDoc}
     */
    public function getCollation() {
        return "SHOW SESSION VARIABLES LIKE 'collation_database'";
    }

    /**
     * MySQL syntax for collation listing
     * {@inheritDoc}
     */
    public function getCollations() {
        return 'SHOW COLLATION';
    }

    /**
     * MySQL syntax for charset listing
     * {@inheritDoc}
     */
    public function getCharsets() {
        return 'SHOW CHARSET';
    }

    /**
     * MySQL syntax for table prefix check
     * {@inheritDoc}
     */
    public function testTablePrefix($database,$prefix) {
        return 'SELECT COUNT('.$this->xpdo->escape('id').') AS '.$this->xpdo->escape('ct').' FROM '.$this->xpdo->escape($database).'.'.$this->xpdo->escape($prefix.'site_content');
    }

    /**
     * MySQL syntax for table truncation
     * {@inheritDoc}
     */
    public function truncate($table) {
        return 'TRUNCATE '.$this->xpdo->escape($table);
    }

    /**
     * MySQL check for server version
     * {@inheritDoc}
     */
    public function verifyServerVersion() {
        $handler = @mysql_connect($this->install->settings->get('database_server'),$this->install->settings->get('database_user'),$this->install->settings->get('database_password'));
        $mysqlVersion = @mysql_get_server_info($handler);
        $mysqlVersion = $this->_sanitizeVersion($mysqlVersion);
        if (empty($mysqlVersion)) {
            return array('result' => 'warning', 'message' => $this->install->lexicon('mysql_version_server_nf'),'version' => $mysqlVersion);
        }

        $mysql_ver_comp = version_compare($mysqlVersion,'4.1.20','>=');
        $mysql_ver_comp_5051 = version_compare($mysqlVersion,'5.0.51','==');
        $mysql_ver_comp_5051a = version_compare($mysqlVersion,'5.0.51a','==');

        if (!$mysql_ver_comp) { /* ancient driver warning */
            return array('result' => 'failure','message' => $this->install->lexicon('mysql_version_fail',array('version' => $mysqlVersion)),'version' => $mysqlVersion);
        } else if ($mysql_ver_comp_5051 || $mysql_ver_comp_5051a) { /* 5.0.51a. bad. berry bad. */
            return array('result' => 'failure','message' => $this->install->lexicon('mysql_version_5051',array('version' => $mysqlVersion)),'version' => $mysqlVersion);
        } else {
            return array('result' => 'success','message' => $this->install->lexicon('mysql_version_success',array('version' => $mysqlVersion)),'version' => $mysqlVersion);
        }
    }

    /**
     * MySQL check for client version
     * {@inheritDoc}
     */
    public function verifyClientVersion() {
        $mysqlVersion = @mysql_get_client_info();
        $mysqlVersion = $this->_sanitizeVersion($mysqlVersion);
        if (empty($mysqlVersion)) {
            return array('result' => 'warning','message' => $this->install->lexicon('mysql_version_client_nf'),'version' => $mysqlVersion);
        }

        $mysql_ver_comp = version_compare($mysqlVersion,'4.1.20','>=');
        if (!$mysql_ver_comp) {
            return array('result' => 'warning','message' => $this->install->lexicon('mysql_version_client_old',array('version' => $mysqlVersion)),'version' => $mysqlVersion);
        } else {
            return array('result' => 'success','message' => $this->install->lexicon('mysql_version_success',array('version' => $mysqlVersion)),'version' => $mysqlVersion);
        }
    }

    /**
     * MySQL syntax to add an index
     * {@inheritDoc}
     */
    public function addIndex($table,$name,$column) {
        return 'ALTER TABLE '.$this->xpdo->escape($table).' ADD INDEX '.$this->xpdo->escape($name).' ('.$this->xpdo->escape($column).')"';
    }

    /**
     * MySQL syntax to drop an index
     * {@inheritDoc}
     */
    public function dropIndex($table,$index) {
        return 'ALTER TABLE '.$this->xpdo->escape($table).' DROP INDEX '.$this->xpdo->escape($index);
    }


    /**
     * Cleans a mysql version string that often has extra junk in certain distros
     *
     * @param string $mysqlVersion The version note to sanitize
     * @return string The sanitized version
     */
    protected function _sanitizeVersion($mysqlVersion) {
        $mysqlVersion = str_replace(array(
            'mysqlnd ',
            '-dev',
            ' ',
        ),'',$mysqlVersion);
        return $mysqlVersion;
    }
}