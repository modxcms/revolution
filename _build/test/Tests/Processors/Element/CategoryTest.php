<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2014 by MODX, LLC.
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
    public function setUp() {
        parent::setUp();
        /** @var modCategory $category */
        $category = $this->modx->newObject('modCategory');
        $category->fromArray(array('category' => 'UnitTestCategory'));
        $category->save();

        $category = $this->modx->newObject('modCategory');
        $category->fromArray(array('category' => 'UnitTestCategory2'));
        $category->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        /** @var modCategory $category */
        $categories = $this->modx->getCollection('modCategory',array(
            'category:LIKE' => 'UnitTest%',
        ));
        foreach ($categories as $category) {
            $category->remove();
        }
        $this->modx->error->reset();
    }

    /**
     * Tests the element/category/create processor, which creates a Category
     *
     * @param boolean $shouldPass
     * @param string $categoryPk
     * @dataProvider providerCategoryCreate
     */
    public function testCategoryCreate($shouldPass,$categoryPk) {
        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'category' => $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $newCategory = $this->modx->getObject('modCategory',array('category' => $categoryPk));
        $passed = $s && $newCategory;
        if (!$shouldPass) {
            $passed = !$passed;
        }
        $this->assertTrue($passed,'Could not create Category: `'.$categoryPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/create processor test.
     * @return array
     */
    public function providerCategoryCreate() {
        return array(
            array(true,'UnitTestCat'),
            array(true,'UnitTestCat2'),
            array(false,'UnitTestCategory'), /* already exists */
            array(false,''),
        );
    }

    /**
     * Tests the element/category/get processor, which gets a Category
     *
     * @param boolean $shouldPass
     * @param string $categoryPk
     * @return boolean
     * @dataProvider providerCategoryGet
     */
    public function testCategoryGet($shouldPass,$categoryPk) {
        /** @var modCategory $category */
        $category = $this->modx->getObject('modCategory',array('category' => $categoryPk));
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $category ? $category->get('id') : $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Category: `'.$categoryPk.'`: '.$result->getMessage());
        return $passed;
    }
    /**
     * Data provider for element/category/create processor test.
     *
     * @return array
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
     * @param boolean $shouldPass
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @dataProvider providerCategoryGetList
     */
    public function testCategoryGetList($shouldPass,$sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
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
     * @return array
     */
    public function providerCategoryGetList() {
        return array(
            array(true,'category','ASC',5,0),
            array(true,'id','ASC',5,0),
            array(true,'category','DESC',null,0),
            array(false,'category','ASC',5,7),
            array(false,'name','ASC',5,0), /* use invalid pk field */
        );
    }

    /**
     * Tests the element/category/remove processor, which removes a Category
     *
     * @param boolean $shouldPass
     * @param string $categoryPk
     * @return boolean
     * @dataProvider providerCategoryRemove
     */
    public function testCategoryRemove($shouldPass,$categoryPk) {
        /** @var modCategory $category */
        $category = $this->modx->getObject('modCategory',array('category' => $categoryPk));
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $category ? $category->get('id') : $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Category: `'.$categoryPk.'`: '.$result->getMessage());
        return $passed;
    }
    /**
     * Data provider for element/category/remove processor test.
     * @return array
     */
    public function providerCategoryRemove() {
        return array(
            array(true,'UnitTestCategory'),
            array(false,234),
            array(false,''),
        );
    }
}
