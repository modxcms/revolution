<?php
/**
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
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
 *
 * @package modx-test
 */
require_once 'Browser/Directory.php';
require_once 'Browser/File.php';
require_once 'Context/Context.php';
require_once 'Context/ContextSetting.php';
/**
 * Suite handling all Processors tests.
 *
 * @package modx-test
 */
class Processors_AllTests extends PHPUnit_Framework_TestSuite {
    public static function suite() {
        $suite = new Processors_AllTests('ProcessorsTest');
        // these tests seem to cause some strange issues..commenting out for now
        //$suite->addTestSuite('BrowserDirectoryProcessors');
        $suite->addTestSuite('BrowserFileProcessors');
        $suite->addTestSuite('ContextProcessors');
        $suite->addTestSuite('ContextSettingProcessors');
        return $suite;
    }
}