<?php
/**
 * Copyright 2010 by MODx, LLC.
 *
 * @package modx-test
 */
require_once 'MODx.php';
/**
 * Suite handling all MODx-class centric tests.
 *
 * @package modx-test
 */
class MODx_AllTests extends PHPUnit_Framework_TestSuite {
    public static function suite() {
        $suite = new MODx_AllTests('MODxClassTest');
        $suite->addTestSuite('MODxTest');
        return $suite;
    }
}