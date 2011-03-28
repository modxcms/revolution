<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2011 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
/**
 * Tests related to element/category/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group Category
 * @group CategoryProcessors
 */
class CategoryProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/category/';

    /**
     * Setup some basic data for this test.
     */
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $modx->error->reset();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory'));
        if ($category) $category->remove();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory2'));
        if ($category) $category->remove();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory'));
        if ($category) $category->remove();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory2'));
        if ($category) $category->remove();
    }

    /**
     * Tests the element/category/create processor, which creates a Category
     * @dataProvider providerCategoryCreate
     */
    public function testCategoryCreate($shouldPass,$categoryPk) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'category' => $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modCategory',array('category' => $categoryPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Category: `'.$categoryPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/create processor test.
     */
    public function providerCategoryCreate() {
        return array(
            array(true,'UnitTestCategory'),
            array(true,'UnitTestCategory2'),
            array(false,'UnitTestCategory2'),
            array(false,''),
        );
    }

    /**
     * Tests the element/category/get processor, which gets a Category
     * @dataProvider providerCategoryGet
     */
    public function testCategoryGet($shouldPass,$categoryPk) {
        $category = $this->modx->getObject('modCategory',array('category' => $categoryPk));
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $category ? $category->get('id') : $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Category: `'.$categoryPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/create processor test.
     */
    public function providerCategoryGet() {
        return array(
            array(true,'UnitTestCategory'),
            array(false,234),
            array(false,''),
        );
    }

    /**
     * Attempts to get a list of Categories
     *
     * @dataProvider providerCategoryGetList
     */
    public function testCategoryGetList($shouldPass,$sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getList',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $passed = !empty($results);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get list of Categories: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/getlist processor test.
     */
    public function providerCategoryGetList() {
        return array(
            array(true,'category','ASC',5,0),
            array(true,'id','ASC',5,0),
            array(true,'category','DESC',null,0),
            array(false,'category','ASC',5,7),
            array(false,'name','ASC',5,0),
        );
    }
    
    /**
     * Tests the element/category/remove processor, which removes a Category
     * @dataProvider providerCategoryRemove
     */
    public function testCategoryRemove($shouldPass,$categoryPk) {
        
        $category = $this->modx->getObject('modCategory',array('category' => $categoryPk));
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $category ? $category->get('id') : $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Category: `'.$categoryPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/remove processor test.
     */
    public function providerCategoryRemove() {
        return array(
            array(true,'UnitTestCategory'),
            array(false,234),
            array(false,''),
        );
    }
}