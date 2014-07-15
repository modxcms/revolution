<?php
/**
 * Copyright 2010-2014 by MODX, LLC.
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
     * Test sortby
     * @dataProvider providerSortBy
     */
    public function testSortBy($sort,$dir,$nameOfFirst) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $success = false;
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->sortby($sort,$dir);
            $result = $this->xpdo->getCollection('Item',$criteria);
            if (is_array($result) && !empty($result)) {
                foreach ($result as $r) { $result = $r; break; }
                $name = $result->get('name');
                $this->assertEquals($nameOfFirst,$name,'xPDOQuery: SortBy did not return expected result, returned `'.$name.'` instead.');
            } else {
                throw new Exception('xPDOQuery: SortBy test getCollection call did not return an array');
            }
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
            array('color,name','ASC','item-03'),
        );
    }

    /**
     * Test sortby with groupby statement
     * @dataProvider providerSortByWithGroupBy
     */
    public function testSortByWithGroupBy($sort,$dir,$nameOfFirst) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->groupby("{$sort},id,color");
            $criteria->sortby($this->xpdo->escape($sort),$dir);
            $criteria->sortby($this->xpdo->escape('id'),'ASC');
            $criteria->sortby($this->xpdo->escape('color'),'ASC');
            $result = $this->xpdo->getCollection('Item',$criteria);
            if (is_array($result) && !empty($result)) {
                $match = null;
                foreach ($result as $r) { $match = $r; break; }
                $name = $match->get('name');
                $this->assertEquals($nameOfFirst,$name,'xPDOQuery: SortBy did not return expected result, returned `'.$name.'` instead.');
            } else {
                throw new Exception('xPDOQuery: SortBy test with groupby call did not return an array');
            }
        } catch (Exception $e) {
            $this->assertTrue(false,$e->getMessage());
        }
    }
    /**
     * Data provider for testSortByWithGroupBy
     * @see testSortByWithGroupBy
     */
    public function providerSortByWithGroupBy() {
        return array(
            array('name','ASC','item-01'),
            array('name','DESC','item-39'),
        );
    }


    /**
     * Test sortby with limit statement
     * @dataProvider providerSortByWithLimit
     */
    public function testSortByWithLimit($sort,$dir,$limit,$start,$nameOfFirst) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $success = false;
        try {
            $criteria = $this->xpdo->newQuery('Item');
            $criteria->sortby($this->xpdo->escape($sort),$dir);
            $criteria->limit($limit,$start);
            $result = $this->xpdo->getCollection('Item',$criteria);
            if (is_array($result) && !empty($result)) {
                foreach ($result as $r) { $result = $r; break; }
                $name = $result->get('name');
                $this->assertEquals($nameOfFirst,$name,'xPDOQuery: SortBy did not return expected result `'.$nameOfFirst.'`, returned `'.$name.'` instead: '.$criteria->toSql());
            } else {
                throw new Exception('xPDOQuery: SortBy test with limit call did not return an array');
            }
        } catch (Exception $e) {
            $this->assertTrue(false,$e->getMessage());
        }
    }
    /**
     * Data provider for testSortByWithGroupBy
     * @see testSortByWithLimit
     */
    public function providerSortByWithLimit() {
        return array(
            array('name','ASC',4,0,'item-01'),
            array('name','DESC',4,0,'item-39'),
        );
    }
}
