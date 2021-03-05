<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
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
 * Provides query abstraction for setup using the sqlite native database driver.
 *
 * @package setup
 * @subpackage drivers
 */
class modInstallDriver_sqlite extends modInstallDriver {
    /**
     * Collations Support
     */    
    private $collations_support = array( 'utf8_general_ns','utf16_general_ns');
    /**
     * Collation Default
     */            
    private $collation_default = 'utf8_general_ci';
    /**
     * Charset Default
     */        
    private $charset_default = 'utf8';
    /**
     * Charsets Support
     */    
    private $charsets_support = array('utf8','utf16'); 
    /**
     * 
     * Check for sqlite extension
     * {@inheritDoc}
     */
    public function verifyExtension() {
        return extension_loaded('sqlite');
    }

    /**
     * Check for sqlite_pdo extension
     * {@inheritDoc}
     */
    public function verifyPDOExtension() {
        return extension_loaded('PDO_sqlite');
    }

    /**
     * SQLite DB syntax for default collation query
     * {@inheritDoc}
     */
    public function getCollation() {
        $collation = $this->collation_default;
        return $collation;
    }

    /**
     * SQLite DB syntax for collation listing
     * {@inheritDoc}
     */
    public function getCollations($collation = '') {        
         
        $collations = array();               
        foreach($this->collations_support as $c_item) {
           $col = array();        
           $col['selected'] = ($c_item==$collation ? ' selected="selected"' : '');
           $col['value'] = $c_item;
           $col['name'] = $c_item;
           $collations[$c_item] = $col;       
        }
        return $collations;
    }

    public function getCharset($collation = '') {
        $charset = $this->charset_default;
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
     * SQLite DB syntax for charset listing
     * {@inheritDoc}
     */
    public function getCharsets($charset = '') {
        $charsets = array();               
        foreach($this->charsets_support as $cs_item) {
           $col = array();        
           $col['selected'] = ($cs_item==$collation ? ' selected="selected"' : '');
           $col['value'] = $cs_item;
           $col['name'] = $cs_item;
           $charsets[$cs_item] = $col;       
        }        
        return $charsets;
    }

    /**
     * SQLite DB syntax for table prefix check
     * {@inheritDoc}
     */
    public function testTablePrefix($database,$prefix) {
        return 'SELECT COUNT('.$this->xpdo->escape('id').') AS '.$this->xpdo->escape('ct').' FROM '.$this->xpdo->escape($prefix.'site_content');
    }

    /**
     * SQLite DB syntax for table truncation
     * {@inheritDoc}
     */
    public function truncate($table) {
        return 'TRUNCATE '.$this->xpdo->escape($table);
    }

    /**
     * SQLite DB check for server version not implement
     *
     * @TODO Get this to actually check the server version.
     *
     * {@inheritDoc}
     */
    public function verifyServerVersion() {
        return array('result' => 'success','message' => $this->install->lexicon('sqlite_version_success',array('version' => '')));
    }

    /**
     * SQLite DB check for client version
     *
     * @TODO Get this to actually check the client version. not implement
     *
     * {@inheritDoc}
     */
    public function verifyClientVersion() {
        return array('result' => 'success', 'message' => $this->install->lexicon('sqlite_version_client_success',array('version' => '')));
    }

    /**
     * SQLite DB syntax to add an index
     * {@inheritDoc}
     */
    public function addIndex($table,$name,$column) {
        return 'ALTER TABLE '.$this->xpdo->escape($table).' ADD INDEX '.$this->xpdo->escape($name).' ('.$this->xpdo->escape($column).')"';
    }

    /**
     * SQLite DB syntax to drop an index
     * {@inheritDoc}
     */
    public function dropIndex($table,$index) {
        return 'ALTER TABLE '.$this->xpdo->escape($table).' DROP INDEX '.$this->xpdo->escape($index);
    }


    /**
     * Cleans a sqlite version string that often has extra junk in certain distros
     *
     * @param string $sqliteVersion The version note to sanitize
     * @return string The sanitized version
     */
    protected function _sanitizeVersion($sqliteVersion) {
        return $sqliteVersion;
    }
}