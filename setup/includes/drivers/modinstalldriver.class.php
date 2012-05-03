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
/**
 * Defines the base driver class and methods required for all derivative
 * driver implementations. All abstract methods must be defined in derivative
 * classes for setup to complete successfully.
 *
 * @package setup
 * @subpackage drivers
 */
abstract class modInstallDriver {
    /**
     * @var modInstall A reference to the modInstall instance
     */
    public $install;
    /**
     * @var xPDO A reference to the xPDO instance
     */
    public $xpdo;

    function __construct(modInstall &$install) {
        $this->install =& $install;
        $this->xpdo =& $install->xpdo;
        $this->install->lexicon->load('drivers');
    }

    /**
     * Grab the default collation for the database.
     *
     * @return string The collation query
     */
    abstract public function getCollation();

    /**
     * Grab a list of collations available to the database.
     *
     * @param string $collation The current/default collation.
     * @return string The collation list query
     */
    abstract public function getCollations($collation = '');

    /**
     * Get the charset from a provided collation, or the default for the driver.
     *
     * @param string $collation A collation to determine charset from.
     * @return string The charset for the provided collation or the default for the driver.
     */
    abstract public function getCharset($collation = '');

    /**
     * Get a list of charsets.
     *
     * @return string The charset list query
     */
    abstract public function getCharsets();

    /**
     * Test the table prefix.
     *
     * @param string $database
     * @param string $prefix
     * @return string The table_prefix testing query
     */
    abstract public function testTablePrefix($database,$prefix);

    /**
     * Verify whether or not the server extension for this driver is installed
     * @return boolean
     */
    abstract public function verifyExtension();
    
    /**
     * Verify whether or not the PDO extension for this driver is installed
     * @return boolean
     */
    abstract public function verifyPDOExtension();
    
    /**
     * Verify client version of driver. Must return array with following indices:
     * - result: Either 'success','warning' or 'failure'
     * - message: The message to return
     * - version: The actual client version
     *
     * @return array
     */
    abstract public function verifyClientVersion();

    /**
     * Verify server version of driver. Must return array with following indices:
     * - result: Either 'success','warning' or 'failure'
     * - message: The message to return
     * - version: The actual server version
     *
     * @return array
     */
    abstract public function verifyServerVersion();

    /**
     * Get query to add an index to a table
     *
     * @param string $table The table
     * @param string $name The name of the index
     * @param string $column The column of the index
     * @return string The SQL statement
     */
    abstract public function addIndex($table,$name,$column);

    /**
     * Get query to drop an index from a table
     *
     * @param string $table The table
     * @param string $index The name of the index
     * @return string The SQL statement
     */
    abstract public function dropIndex($table,$index);
    
    /**
     * Truncate a table
     *
     * @param string $table The table name
     */
    abstract public function truncate($table);
}
