<?php
/**
 * Copyright 2010-2013 by MODX, LLC.
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
 * Tests related to having statements.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQueryHavingTest extends xPDOTestCase {
    /**
     * Setup dummy data for each test.
     */
    public function setUp() {
        parent::setUp();
        try {
            /* ensure we have clear data and identity sequences */
            $this->xpdo->getManager();
            $this->xpdo->manager->createObjectContainer('Item');

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
    	$this->xpdo->getManager();
        $this->xpdo->manager->removeObjectContainer('Item');
        parent::tearDown();
    }

    /**
     * Test getCount with a groupby set.
     */
    public function testGetCountWithGroupBy() {
        $criteria = $this->xpdo->newQuery('Item');
        $criteria->select(array(
            'color' => $this->xpdo->escape('color'),
            'color_count' => "COUNT({$this->xpdo->escape('id')})"
        ));
        $criteria->groupby('color');
        $actual = $this->xpdo->getCount('Item', $criteria);
        $this->assertEquals(4, $actual);
    }

    /**
     * Test having
     * @dataProvider providerHaving
     */
    public function testHaving($having, $nameOfFirst) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $success = false;
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->groupby('id');
            $criteria->groupby('name');
            $criteria->groupby('color');
            $criteria->having($having);
            $result = $this->xpdo->getCollection('Item',$criteria);
            if (is_array($result) && !empty($result)) {
                foreach ($result as $r) { $result = $r; break; }
                $name = $result->get('name');
                $this->assertEquals($nameOfFirst,$name,'xPDOQuery: Having clause did not return expected result, returned `'.$name.'` instead.');
            } else {
                throw new Exception('xPDOQuery: Having test getCollection call did not return an array');
            }
        } catch (Exception $e) {
            $this->assertTrue(false,$e->getMessage());
        }
    }
    /**
     * Data provider for testHaving
     * @see testHaving
     */
    public function providerHaving() {
        return array(
            array(array('color' => 'red'),'item-04'),
            array(array('color' => 'green'),'item-01'),
            array(array('id:<' => 3, 'AND:id:>' => 1),'item-02'),
        );
    }

    /**
     * Test having with groupby statement
     * @dataProvider providerHavingWithGroupBy
     */
    public function testHavingWithGroupBy($having,$nameOfFirst) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->groupby('name');
            $criteria->having($having);
            $result = $this->xpdo->getCollection('Item',$criteria);
            if (is_array($result) && !empty($result)) {
                $match = null;
                foreach ($result as $r) { $match = $r; break; }
                $name = $match->get('name');
                $this->assertEquals($nameOfFirst,$name,'xPDOQuery: Having did not return expected result, returned `'.$name.'` instead.');
            } else {
                throw new Exception('xPDOQuery: Having test with groupby call did not return an array');
            }
        } catch (Exception $e) {
            $this->assertTrue(false,$e->getMessage());
        }
    }
    /**
     * Data provider for testHavingWithGroupBy
     * @see testHavingWithGroupBy
     */
    public function providerHavingWithGroupBy() {
        return array(
            array(array('color' => 'red'),'item-04'),
            array(array('color' => 'green'),'item-01'),
            array(array('id:<' => 3, 'AND:id:>' => 1),'item-02'),
        );
    }


    /**
     * Test having with limit statement
     * @dataProvider providerHavingWithLimit
     */
    public function testHavingWithLimit($having,$limit,$start,$nameOfFirst) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $success = false;
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->groupby('id');
            $criteria->groupby('name');
            $criteria->groupby('color');
            $criteria->having($having);
            $criteria->limit($limit,$start);
            $result = $this->xpdo->getCollection('Item',$criteria);
            if (is_array($result) && !empty($result)) {
                foreach ($result as $r) { $result = $r; break; }
                $name = $result->get('name');
                $this->assertEquals($nameOfFirst,$name,'xPDOQuery: Having did not return expected result `'.$nameOfFirst.'`, returned `'.$name.'` instead: '.$criteria->toSql());
            } else {
                throw new Exception('xPDOQuery: Having test with limit call did not return an array');
            }
        } catch (Exception $e) {
            $this->assertTrue(false,$e->getMessage());
        }
    }
    /**
     * Data provider for testHavingWithGroupBy
     * @see testHavingWithLimit
     */
    public function providerHavingWithLimit() {
        return array(
            array(array('color' => 'red'),1,0,'item-04'),
            array(array('color' => 'green'),1,0,'item-01'),
            array(array('id:<' => 3, 'AND:id:>' => 1),1,0,'item-02'),
        );
    }
}
