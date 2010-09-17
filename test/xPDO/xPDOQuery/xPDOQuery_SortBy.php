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
 * Tests related to sortby statements.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQuerySortByTest extends xPDOTestCase {
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
                $idx = str_pad($i,2,'0',STR_PAD_LEFT);
                $item->set('name','item-'.$idx);
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
     * Clean up data when through.
     */
    public function tearDown() {
        $this->xpdo->removeCollection('Item',array());
        parent::tearDown();
    }

    /**
     * Test sortby
     * @dataProvider providerSortBy
     */
    public function testSortBy($sort,$dir,$nameOfFirst) {
        $success = false;
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->sortby($sort,$dir);
            $result = $this->xpdo->getCollection('Item',$criteria);
            if (is_array($result) && !empty($result)) {
                foreach ($result as $r) { $result = $r; break; }
                $name = $result->get('name');
            } else {
                throw new Exception('xPDOQuery: SortBy test getCollection call did not return an array');
            }
            $this->assertEquals($nameOfFirst,$name,'xPDOQuery: SortBy did not return expected result, returned `'.$name.'` instead.');
        } catch (Exception $e) {
            $this->assertTrue(false,$e->getMessage());
        }
    }
    /**
     * Data provider for testLimit
     * @see testLimit
     */
    public function providerSortBy() {
        return array(
            array('name','ASC','item-01'),
            array('name','DESC','item-39'),
        );
    }
}