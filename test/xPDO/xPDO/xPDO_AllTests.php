<?php
/**
 * @package xpdo-test
 * @subpackage xpdo
 */
require_once 'xPDO.php';
require_once 'xPDOObject.php';
/**
 * Suite handling all xPDO-class centric tests.
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDO_AllTests extends PHPUnit_Framework_TestSuite {
    public static function suite() {
        $suite = new xPDO_AllTests('xPDOClassTest');
        $suite->addTestSuite('xPDOTest');
        $suite->addTestSuite('xPDOObjectTest');
        return $suite;
    }
}