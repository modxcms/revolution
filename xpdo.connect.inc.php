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
 * Contains a try/catch block for catching native PDO connection exceptions.
 *
 * This is only used in PHP 5 with native PDO.
 *
 * @package xpdo
 */
try {
    $this->pdo= new $pdo_classname($this->config['dsn'], $this->config['username'], $this->config['password'], $this->config['driverOptions']);
    $errorCode= $this->pdo->errorCode();
} catch (PDOException $xe) {
    $this->pdo= null;
}
return (is_object($this->pdo) && (empty($errorCode) || $errorCode == PDO_ERR_NONE));