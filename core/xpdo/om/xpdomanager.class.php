<?php
/*
 * Copyright 2010-2014 by MODX, LLC.
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
     * Creates the physical container representing a data source.
     *
     * @param array $dsnArray An array of xPDO configuration properties.
     * @param string $username Database username with privileges to create tables.
     * @param string $password Database user password.
     * @param array $containerOptions An array of options for controlling the creation of the container.
     * @return boolean True if the database is created successfully or already exists.
     */
    abstract public function createSourceContainer($dsnArray = null, $username= null, $password= null, $containerOptions= array ());

    /**
     * Drops a physical data source container, if it exists.
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
     * An object container is a synonym for a database table.
     *
     * @param string $className The class of object to create a source container for.
     * @return boolean Returns true on successful creation, false on failure.
     */
    abstract public function createObjectContainer($className);

    /**
     * Alter the structure of an existing persistent data object container.
     *
     * @param string $className The class of object to alter the container of.
     * @param array $options An array of options describing the alterations to be made.
     * @return boolean Returns true on successful alteration, false on failure.
     */
    abstract public function alterObjectContainer($className, array $options = array());

    /**
     * Drop an object container (i.e. database table), if it exists.
     *
     * @param string $className The object container to drop.
     * @return boolean Returns true on successful drop, false on failure.
     */
    abstract public function removeObjectContainer($className);

    /**
     * Add a field to an object container, e.g. ADD COLUMN.
     *
     * @param string $class The object class to add the field to.
     * @param string $name The name of the field to add.
     * @param array $options An array of options for the process.
     * @return boolean True if the column is added successfully, otherwise false.
     */
    abstract public function addField($class, $name, array $options = array());

    /**
     * Alter an existing field of an object container, e.g. ALTER COLUMN.
     *
     * @param string $class The object class to alter the field of.
     * @param string $name The name of the field to alter.
     * @param array $options An array of options for the process.
     * @return boolean True if the column is altered successfully, otherwise false.
     */
    abstract public function alterField($class, $name, array $options = array());

    /**
     * Remove a field from an object container, e.g. DROP COLUMN.
     *
     * @param string $class The object class to drop the field from.
     * @param string $name The name of the field to drop.
     * @param array $options An array of options for the process.
     * @return boolean True if the column is dropped successfully, otherwise false.
     */
    abstract public function removeField($class, $name, array $options = array());

    /**
     * Add an index to an object container, e.g. ADD INDEX.
     *
     * @param string $class The object class to add the index to.
     * @param string $name The name of the index to add.
     * @param array $options An array of options for the process.
     * @return boolean True if the index is added successfully, otherwise false.
     */
    abstract public function addIndex($class, $name, array $options = array());

    /**
     * Remove an index from an object container, e.g. DROP INDEX.
     *
     * @param string $class The object class to drop the index from.
     * @param string $name The name of the index to drop.
     * @param array $options An array of options for the process.
     * @return boolean True if the index is dropped successfully, otherwise false.
     */
    abstract public function removeIndex($class, $name, array $options = array());

    /**
     * Add a constraint to an object container, e.g. ADD CONSTRAINT.
     *
     * @param string $class The object class to add the constraint to.
     * @param string $name The name of the constraint to add.
     * @param array $options An array of options for the process.
     * @return boolean True if the constraint is added successfully, otherwise false.
     */
    abstract public function addConstraint($class, $name, array $options = array());

    /**
     * Remove a constraint from an object container, e.g. DROP CONSTRAINT.
     *
     * @param string $class The object class to drop the constraint from.
     * @param string $name The name of the constraint to drop.
     * @param array $options An array of options for the process.
     * @return boolean True if the constraint is dropped successfully, otherwise false.
     */
    abstract public function removeConstraint($class, $name, array $options = array());

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

    /**
     * Get the SQL necessary to define a column for a specific database engine.
     *
     * @param string $class The name of the class the column represents a field of.
     * @param string $name The name of the field and physical database column.
     * @param string $meta The metadata defining the field.
     * @param array $options An array of options for the process.
     * @return string A string of SQL representing the column definition.
     */
    abstract protected function getColumnDef($class, $name, $meta, array $options = array());

    /**
     * Get the SQL necessary to define an index for a specific database engine.
     *
     * @param string $class The name of the class the index is defined for.
     * @param string $name The name of the index.
     * @param string $meta The metadata defining the index.
     * @param array $options An array of options for the process.
     * @return string A string of SQL representing the index definition.
     */
    abstract protected function getIndexDef($class, $name, $meta, array $options = array());
}
