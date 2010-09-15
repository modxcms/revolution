<?php
/**
 * @package modx-test
 */
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/MODx/MODx_AllTests.php';
require_once dirname(__FILE__).'/Processors/Processors_AllTests.php';
/**
 * Main Suite handling all MODx-related (non-environment) tests.
 *
 * @package modx-test
 */
class MODxSuite_AllTests {
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('xPDOSuite');
        $suite->addTest(MODx_AllTests::suite());
        $suite->addTest(Processors_AllTests::suite());

        return $suite;
    }
}