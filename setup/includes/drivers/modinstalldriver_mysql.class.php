<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
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
     * MySQL only needs PDO extension
     * {@inheritDoc}
     */
    public function verifyExtension() {
        return true;
    }

    /**
     * MySQL check for mysql_pdo extension
     * {@inheritDoc}
     */
    public function verifyPDOExtension() {
        return extension_loaded('pdo_mysql');
    }

    /**
     * MySQL process for getting the default collation
     * {@inheritDoc}
     */
    public function getCollation() {
        $collation = 'utf8_bin';
        $stmt = $this->xpdo->query("SHOW SESSION VARIABLES LIKE 'collation_database'");
        if ($stmt && $stmt instanceof PDOStatement) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $collation = $row['Value'];
            $stmt->closeCursor();
        }
        return $collation;
    }

    /**
     * MySQL collation listing
     * {@inheritDoc}
     */
    public function getCollations($collation = '') {
        $collations = null;
        $stmt = $this->xpdo->query("SHOW COLLATION");
        if ($stmt && $stmt instanceof PDOStatement) {
            $collations = array();
            if (empty($collation)) $collation = $this->getCollation();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $col = array();
                $col['selected'] = ($row['Collation']==$collation ? ' selected="selected"' : '');
                $col['value'] = $row['Collation'];
                $col['name'] = $row['Collation'];
                $collations[$row['Collation']] = $col;
            }
            ksort($collations);
        }
        return $collations;
    }

    /**
     * Get the MySQL charset based on collation, or default.
     * {@inheritDoc}
     */
    public function getCharset($collation = '') {
        $charset = 'utf8';
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
     * Get charset listing for MySQL.
     * {@inheritDoc}
     */
    public function getCharsets($charset = '') {
        $charsets = null;
        $stmt = $this->xpdo->query('SHOW CHARSET');
        if ($stmt && $stmt instanceof PDOStatement) {
            $charsets = array();
            if (empty($charset)) $charset = $this->getCharset();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $col = array();
                $col['selected'] = $row['Charset']==$charset ? ' selected="selected"' : '';
                $col['value'] = $row['Charset'];
                $col['name'] = $row['Charset'];
                $charsets[$row['Charset']] = $col;
            }
            ksort($charsets);
        }
        return $charsets;
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
        $version = $this->getServerVersion();
        $isMariaDB = stripos($version, 'mariadb') !== false;

        $mysqlVersion = $this->_sanitizeVersion($version);
        if (empty($mysqlVersion)) {
            $config_options = $this->install->settings->get('config_options');
            $config_options[xPDO::OPT_OVERRIDE_TABLE_TYPE] = 'MyISAM';
            $this->install->settings->set('config_options', $config_options);
            $this->install->settings->store();

            return array('result' => 'warning', 'message' => $this->install->lexicon('mysql_version_server_nf'),'version' => $mysqlVersion);
        }

        $mysql_ver_comp = version_compare($mysqlVersion,'4.1.20','>=');
        $mysql_ver_comp_5051 = version_compare($mysqlVersion,'5.0.51','==');
        $mysql_ver_comp_5051a = version_compare($mysqlVersion,'5.0.51a','==');

        if ($isMariaDB) {
            $mysql_ver_comp_myisam = version_compare($mysqlVersion, '10.0.5', '<');
        } else {
            $mysql_ver_comp_myisam = version_compare($mysqlVersion, '5.6', '<');
        }

        if ($mysql_ver_comp_myisam) {
            $config_options = $this->install->settings->get('config_options');
            $config_options[xPDO::OPT_OVERRIDE_TABLE_TYPE] = 'MyISAM';
            $this->install->settings->set('config_options', $config_options);
            $this->install->settings->store();
        }

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
        $mysqlVersion = $this->xpdo->getAttribute(PDO::ATTR_CLIENT_VERSION);
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

    protected function getServerVersion() {
        try {
            $stmt = $this->xpdo->query('SELECT VERSION();');
            $value = $this->xpdo->getValue($stmt);
        } catch (Exception $e) {
            $value = $this->xpdo->getAttribute(PDO::ATTR_SERVER_VERSION);
        }

        return $value;
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
