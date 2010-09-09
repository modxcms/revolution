<?php
/**
 * @package xpdo-test
 * @subpackage xpdosuite
 */
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/xPDO/xPDO_AllTests.php';
require_once dirname(__FILE__).'/xPDOQuery/xPDOQuery_AllTests.php';
/**
 * Main Suite handling all xPDO tests.
 *
 * @package xpdo-test
 * @subpackage xpdosuite
 */
class xPDOSuite_AllTests {
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('xPDOSuite');
        $suite->addTest(xPDO_AllTests::suite());
        $suite->addTest(xPDOQuery_AllTests::suite());

        return $suite;
    }
}