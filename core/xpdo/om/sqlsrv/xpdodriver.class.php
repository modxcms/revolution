<?php
/*
 * Copyright 2010-2015 by MODX, LLC.
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
 * The sqlsrv implementation of the xPDODriver class.
 *
 * @package xpdo
 * @subpackage om.sqlsrv
 */

/**
 * Include the parent {@link xPDODriver} class.
 */
require_once (dirname(__DIR__) . '/xpdodriver.class.php');

/**
 * Provides sqlsrv driver abstraction for an xPDO instance.
 *
 * This is baseline metadata and methods used throughout the framework.  xPDODriver
 * class implementations are specific to a PDO driver and this instance is
 * implemented for sqlsrv.
 *
 * @package xpdo
 * @subpackage om.sqlsrv
 */
class xPDODriver_sqlsrv extends xPDODriver {
    public $quoteChar = "'";
    public $escapeOpenChar = '[';
    public $escapeCloseChar = ']';
    public $_currentTimestamps= array(
        "CURRENT_TIMESTAMP",
        "GETDATE()"
    );
    public $_currentDates= array(
        "CURRENT_DATE"
    );
    public $_currentTimes= array(
        "CURRENT_TIME"
    );

    /**
     * Get a sqlsrv xPDODriver instance.
     *
     * @param xPDO &$xpdo A reference to a specific xPDO instance.
     */
    function __construct(xPDO &$xpdo) {
        parent :: __construct($xpdo);
        $this->dbtypes['integer']= array('/INT$/i');
        $this->dbtypes['float']= array('/^DEC/i','/^NUMERIC$/i','/^FLOAT$/i','/^REAL$/i','/MONEY$/i');
        $this->dbtypes['string']= array('/CHAR$/i','/TEXT$/i');
        $this->dbtypes['date']= array('/^DATE$/i');
        $this->dbtypes['datetime']= array('/DATETIME/i');
        $this->dbtypes['time']= array('/^TIME$/i');
        $this->dbtypes['binary']= array('/BINARY$/i','/^IMAGE$/i');
        $this->dbtypes['bit']= array('/^BIT$/i');
    }
}
