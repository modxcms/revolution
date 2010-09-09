<?php
/**
 * @package xpdo-test
 * @subpackage xpdo
 */
require_once 'xPDO.php';
/**
 * Suite handling all xPDO-class centric tests.
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDO_AllTests extends PHPUnit_Framework_TestSuite {
    public static function suite() {
        return new xPDO_AllTests('xPDOTest');
    }
}