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
 * The sqlite implementation of the xPDODriver class.
 *
 * @package xpdo
 * @subpackage om.sqlite
 */

/**
 * Include the parent {@link xPDODriver} class.
 */
require_once (dirname(dirname(__FILE__)) . '/xpdodriver.class.php');

/**
 * Provides sqlite driver abstraction for an xPDO instance.
 *
 * This is baseline metadata and methods used throughout the framework.  xPDODriver
 * class implementations are specific to a PDO driver and this instance is
 * implemented for sqlite.
 *
 * @package xpdo
 * @subpackage om.sqlite
 */
class xPDODriver_sqlite extends xPDODriver {
    public $quoteChar = "'";
    public $escapeOpenChar = '"';
    public $escapeCloseChar = '"';
    public $_currentTimestamps= array(
        "CURRENT_TIMESTAMP"
    );
    public $_currentDates= array(
        "CURRENT_DATE"
    );
    public $_currentTimes= array(
        "CURRENT_TIME"
    );

    /**
     * Get a sqlite xPDODriver instance.
     *
     * @param xPDO &$xpdo A reference to a specific xPDO instance.
     */
    function __construct(xPDO &$xpdo) {
        parent :: __construct($xpdo);
        $this->dbtypes['integer']= array('/INT/i');
        $this->dbtypes['string']= array('/CHAR/i','/CLOB/i','/TEXT/i', '/ENUM/i');
        $this->dbtypes['float']= array('/REAL/i','/FLOA/i','/DOUB/i');
        $this->dbtypes['datetime']= array('/TIMESTAMP/i','/DATE/i');
        $this->dbtypes['binary']= array('/BLOB/i');
    }
}
