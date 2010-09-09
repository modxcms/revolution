<?php
/**
 * @package xpdo-test
 * @subpackage xpdoquery
 */
require_once 'xPDOQuery.php';
/**
 * Suite handling all xPDOQuery-class centric tests.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQuery_AllTests extends PHPUnit_Framework_TestSuite
{
    public static function suite() {
        return new xPDOQuery_AllTests('xPDOQueryTest');
    }
}