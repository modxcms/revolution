<?php
/**
 * Tests related to the xPDOQuery class.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQueryTest extends xPDOTestCase {

    /**
     * Verify xPDO::newQuery returns a xPDOQuery object
     */
    public function testNewQuery() {
        $this->xpdo = xPDOTestHarness::_getConnection();
        print __METHOD__." - Test newQuery method.\n";        
        $criteria = $this->xpdo->newQuery('Person');
        $success = is_object($criteria) && $criteria instanceof xPDOQuery;
        $this->assertTrue($success);
    }
}