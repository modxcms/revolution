<?php
/*
 * Copyright 2006-2010 by  Jason Coward <xpdo@opengeek.com>
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * The xPDOManager class provides data source management.
 *
 * @package xpdo
 * @subpackage om
 */

/**
 * Provides data source management for an xPDO instance.
 *
 * These are utility functions that only need to be loaded under special
 * circumstances, such as creating tables, adding indexes, altering table
 * structures, etc.  xPDOManager class implementations are specific to a
 * database driver and should include this base class in order to extend it.
 *
 * @abstract
 * @package xpdo
 * @subpackage om
 */
abstract class xPDOManager {
    /**
     * @var xPDO A reference to the XPDO instance using this manager.
     * @access public
     */
    public $xpdo= null;
    /**
     * @var xPDOGenerator The generator class for forward and reverse
     * engineering tasks (loaded only on demand).
     */
    public $generator= null;
    /**
     * @var xPDOTransport The data transport class for migrating data.
     */
    public $transport= null;
    /**
     * @var array Describes the physical database types.
     */
    public $dbtypes= array ();

    /**
     * Get a xPDOManager instance.
     *
     * @param object $xpdo A reference to a specific modDataSource instance.
     */
    public function __construct(& $xpdo) {
        if ($xpdo !== null && $xpdo instanceof xPDO) {
            $this->xpdo= & $xpdo;
        }
    }

    /**
     * Gets the PHP field type based upon the specified database type.
     *
     * @access public
     * @param string $dbtype The database field type to convert.
     * @return string The associated PHP type
     */
    public function getPhpType($dbtype) {
        $phptype = 'string';
        if ($dbtype !== null) {
            foreach ($this->dbtypes as $type => $patterns) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $dbtype)) {
                        $phptype = $type;
                        break 2;
                    }
                }
            }
        }
        return $phptype;
    }

    /**
     * Creates the physical data container represented by a data source.
     *
     * @param array $dsnArray An array of xPDO configuration properties.
     * @param string $username Database username with privileges to create tables.
     * @param string $password Database user password.
     * @param array $containerOptions An array of options for controlling the creation of the container.
     * @return boolean True if the database is created successfully or already exists.
     */
    abstract public function createSourceContainer($dsnArray = null, $username= null, $password= null, $containerOptions= array ());

    /**
     * Drops a physical data container, if it exists.
     *
     * @param string $dsn Represents the database connection string.
     * @param string $username Database username with privileges to drop tables.
     * @param string $password Database user password.
     * @return boolean Returns true on successful drop, false on failure.
     */
    abstract public function removeSourceContainer($dsnArray = null, $username= null, $password= null);

    /**
     * Creates the container for a persistent data object.
     *
     * A source container is a synonym for a database table.
     *
     * @param string $className The class of object to create a source container for.
     * @return boolean Returns true on successful creation, false on failure.
     */
    abstract public function createObjectContainer($className);

    /**
     * Drops a table, if it exists.
     *
     * @param string $className The object table to drop.
     * @return boolean Returns true on successful drop, false on failure.
     */
    abstract public function removeObjectContainer($className);

    /**
     * Gets an XML schema parser / generator for this manager instance.
     *
     * @return xPDOGenerator A generator class for this manager.
     */
    public function getGenerator() {
        if ($this->generator === null || !$this->generator instanceof xPDOGenerator) {
            $loaded= include_once(XPDO_CORE_PATH . 'om/' . $this->xpdo->config['dbtype'] . '/xpdogenerator.class.php');
            if ($loaded) {
                $generatorClass = 'xPDOGenerator_' . $this->xpdo->config['dbtype'];
                $this->generator= new $generatorClass ($this);
            }
            if ($this->generator === null || !$this->generator instanceof xPDOGenerator) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load xPDOGenerator [{$generatorClass}] class.");
            }
        }
        return $this->generator;
    }

    /**
     * Gets a data transport mechanism for this xPDOManager instance.
     */
    public function getTransport() {
        if ($this->transport === null || !$this->transport instanceof xPDOTransport) {
            if (!isset($this->xpdo->config['xPDOTransport.class']) || !$transportClass= $this->xpdo->loadClass($this->xpdo->config['xPDOTransport.class'], '', false, true)) {
                $transportClass= $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
            }
            if ($transportClass) {
                $this->transport= new $transportClass ($this);
            }
            if ($this->transport === null || !$this->transport instanceof xPDOTransport) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load xPDOTransport [{$transportClass}] class.");
            }
        }
        return $this->transport;
    }
}
