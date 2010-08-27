<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009, 2010 by the MODx Team.
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
 * Provides query abstraction for setup using the MySQL database
 *
 * @package setup
 * @subpackage drivers
 */
class modInstallDriver_mysql extends modInstallDriver {
    protected $_escapeChar = '`';

    public function getCollation() {
        return "SHOW SESSION VARIABLES LIKE 'collation_database'";
    }
    public function getCollations() {
        return 'SHOW COLLATION';
    }
    public function getCharsets() {
        return 'SHOW CHARSET';
    }
    public function testTablePrefix($database,$prefix) {
        return 'SELECT COUNT(`id`) AS ct FROM `'.trim($database,'`').'`.`'.$prefix.'site_content`';
    }
    public function verifyExtension() {
        return extension_loaded('mysql') && function_exists('mysql_connect');
    }
    public function verifyPDOExtension() {
        return extension_loaded('pdo_mysql');
    }

    /* table manipulation queries */
    public function addIndex($table,$name,$column) {
        return 'ALTER TABLE '.$this->escape($table).' ADD INDEX '.$this->escape($name).' ('.$this->escape($column).')"';
    }
    public function dropIndex($table,$index) {
        return 'ALTER TABLE '.$this->escape($table).' DROP INDEX '.$this->escape($index);
    }
}