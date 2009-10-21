<?php
/*
 * Copyright 2006, 2007, 2008, 2009 by  Jason Coward <xpdo@opengeek.com>
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
class xPDOManager {
    /**
     * @var xPDO A reference to the XPDO instance using this manager.
     * @access public
     */
    var $xpdo= null;
    /**
     * @var xPDOGenerator The generator class for forward and reverse
     * engineering tasks (loaded only on demand).
     */
    var $generator= null;
    /**
     * @var xPDOTransport The data transport class for migrating data.
     */
    var $transport= null;
    /**
     * @var array Describes the physical database types.
     */
    var $dbtypes= array ();

    var $action; // legacy action directive

    /**
     * Get a xPDOManager instance.
     *
     * @param object $xpdo A reference to a specific modDataSource instance.
     */
    function xPDOManager(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        if ($xpdo !== null && is_a($xpdo, 'xPDO')) {
            $this->xpdo= & $xpdo;
        }
    }

    /**
     * Creates the physical data container represented by a data source
     *
     * @todo Refactor this to work on an xPDO instance rather than as a static call.
     */
    function createSourceContainer($dsn, $username= '', $password= '', $containerOptions= null) {
        $created= false;
        if ($dsnArray= xPDO :: parseDSN($dsn)) {
            switch ($dsnArray['dbtype']) {
            	case 'mysql':
                    include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/mysql/xpdomanager.class.php');
            		$created= xPDOManager_mysql :: createSourceContainer($dsnArray, $username, $password, $containerOptions);
            		break;
            	case 'sqlite':
                    include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/sqlite/xpdomanager.class.php');
            		$created= xPDOManager_sqlite :: createSourceContainer($dsnArray, $username, $password, $containerOptions);
            		break;
            	case 'pgsql':
                    include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/pgsql/xpdomanager.class.php');
            		$created= xPDOManager_pgsql :: createSourceContainer($dsnArray, $username, $password, $containerOptions);
            		break;
            	default:
            		break;
            }
        }
        return $created;
    }

    /**
     * Gets an XML schema parser / generator for this manager instance.
     *
     * @return xPDOGenerator A generator class for this manager.
     */
    function getGenerator() {
        if ($this->generator === null || !is_a($this->generator, 'xPDOGenerator')) {
            if (!isset($this->xpdo->config['xPDOGenerator.'.$this->xpdo->config['dbtype'].'.class']) || !$generatorClass= $this->xpdo->loadClass($this->xpdo->config['xPDOGenerator.'.$this->xpdo->config['dbtype'].'.class'], '', false, true)) {
                $generatorClass= $this->xpdo->loadClass($this->xpdo->config['dbtype'] . '.xPDOGenerator', '', false, true);
            }
            if ($generatorClass) {
                $generatorClass .= '_' . $this->xpdo->config['dbtype'];
                $this->generator= new $generatorClass ($this);
            }
            if ($this->generator === null || !is_a($this->generator, 'xPDOGenerator')) {
                $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "Could not load xPDOGenerator [{$generatorClass}] class.");
            }
        }
        return $this->generator;
    }

    /**
     * Gets a data transport mechanism for this xPDOManager instance.
     */
    function getTransport() {
        if ($this->transport === null || !is_a($this->transport, 'xPDOTransport')) {
            if (!isset($this->xpdo->config['xPDOTransport.class']) || !$transportClass= $this->xpdo->loadClass($this->xpdo->config['xPDOTransport.class'], '', false, true)) {
                $transportClass= $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
            }
            if ($transportClass) {
                $this->transport= new $transportClass ($this);
            }
            if ($this->transport === null || !is_a($this->transport, 'xPDOTransport')) {
                $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "Could not load xPDOTransport [{$transportClass}] class.");
            }
        }
        return $this->transport;
    }
}
