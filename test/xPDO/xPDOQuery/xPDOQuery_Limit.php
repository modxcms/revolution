<?php
/**
 * Copyright 2010 by MODx, LLC.
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package xpdo-test
 */
/**
 * Tests related to limit statements.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQueryLimitTest extends xPDOTestCase {
    /**
     * Setup dummy data for each test.
     */
    public function setUp() {
        parent::setUp();
        try {
            /* ensure we have clear data */
            $this->xpdo->removeCollection('Item',array());
            $colors = array('red','green','yellow','blue');

            $r = 0;
            for ($i=1;$i<40;$i++) {
                $item = $this->xpdo->newObject('Item');
                $item->set('name','item-'.$i);
                $r++;
                if ($r > 3) $r = 0;
                $item->set('color',$colors[$r]);
                $item->save();
            }

        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }

    /**
     * Test limit
     * @dataProvider providerLimit
     * @param int $limit A number to limit by
     * @param int $limit The index to start on
     * @param boolean $shouldEqual If the result count should equal the limit
     */
    public function testLimit($limit,$start = 0,$shouldEqual = true) {
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->limit($limit,$start);
            $result = $this->xpdo->getCollection('Item',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $success = count($result) == $limit;
        if (!$shouldEqual) $success = !$success;
        $this->assertTrue($success,'xPDOQuery: Limit clause returned more than desired '.$limit.' result.');
    }
    /**
     * Data provider for testLimit
     * @see testLimit
     */
    public function providerLimit() {
        return array(
            array(1,0,true), /* limit 1, start at 0 */
            array(2,0,true), /* limit 2, start at 0 */
            array(50,0,false), /* limit 50, start at 0, should not match */
            array(50,45,false), /* limit 50, start at 45, should not match */
            array(5,2,true), /* limit 5, start at 2 */
        );
    }
}