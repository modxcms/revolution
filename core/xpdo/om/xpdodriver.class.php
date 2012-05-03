<?php
/*
 * Copyright 2010-2012 by MODX, LLC.
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
 * The xPDODriver class provides the baseline driver specific abstraction.
 *
 * @package xpdo
 * @subpackage om
 */

/**
 * Provides driver specific members and methods for an xPDO instance.
 *
 * These are baseline members and methods that need to be loaded every 
 * time an xPDO instance makes a connection.  xPDODriver class implementations 
 * are specific to a database driver and should include this base class in order
 * to extend it.
 *
 * @abstract
 * @package xpdo
 * @subpackage om
 */
abstract class xPDODriver {
    /**
     * @var xPDO A reference to the XPDO instance using this manager.
     * @access public
     */
    public $xpdo= null;
    /**
     * @var array Describes the physical database types.
     */
    public $dbtypes= array ();
    /**
     * An array of DB constants/functions that represent timestamp values.
     * @var array
     */
    public $_currentTimestamps= array();
    /**
     * An array of DB constants/functions that represent date values.
     * @var array
     */
    public $_currentDates= array();
    /**
     * An array of DB constants/functions that represent time values.
     * @var array
     */
    public $_currentTimes= array();
    public $quoteChar = '';
    public $escapeOpenChar = '';
    public $escapeCloseChar = '';

    /**
     * Get an xPDODriver instance.
     *
     * @param xPDO $xpdo A reference to a specific xPDO instance.
     */
    public function __construct(xPDO &$xpdo) {
        if ($xpdo !== null && $xpdo instanceof xPDO) {
            $this->xpdo= & $xpdo;
            $this->xpdo->_quoteChar= $this->quoteChar;
            $this->xpdo->_escapeCharOpen= $this->escapeOpenChar;
            $this->xpdo->_escapeCharClose= $this->escapeCloseChar;
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
}
