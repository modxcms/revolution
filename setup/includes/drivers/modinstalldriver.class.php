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
/**
 * Defines the base driver class and methods required for all derivative
 * driver implementations. All abstract methods must be defined in derivative
 * classes for setup to complete successfully.
 *
 * @package setup
 * @subpackage drivers
 */
abstract class modInstallDriver {
    protected $_escapeChar = '`';

    function __construct(modInstall &$install) {
        $this->install =& $install;
        $this->install->lexicon->load('drivers');
    }

    abstract public function getCollation();
    abstract public function getCollations();
    abstract public function getCharsets();
    abstract public function testTablePrefix($database,$prefix);
    abstract public function verifyExtension();
    abstract public function verifyPDOExtension();
    abstract public function addIndex($table,$name,$column);
    abstract public function dropIndex($table,$index);

    protected function escape($str) {
        $string = trim($string, $this->_escapeChar);
        return $this->_escapeChar . $string . $this->_escapeChar;
    }
}
