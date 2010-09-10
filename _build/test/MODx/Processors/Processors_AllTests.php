<?php
/**
 * Copyright 2010 by MODx, LLC.
 *
 * @package modx-test
 */
require_once 'Browser/Directory.php';
/**
 * Suite handling all Processors tests.
 *
 * @package modx-test
 */
class Processors_AllTests extends PHPUnit_Framework_TestSuite {
    public static function suite() {
        $suite = new Processors_AllTests('MODxClassTest');
        $suite->addTestSuite('BrowserDirectoryProcessors');
        return $suite;
    }
}