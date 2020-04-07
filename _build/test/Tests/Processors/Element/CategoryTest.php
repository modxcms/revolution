<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/
namespace MODX\Revolution\Tests\Processors\Element;


use MODX\Revolution\modCategory;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Element\Category\Create;
use MODX\Revolution\Processors\Element\Category\Get;
use MODX\Revolution\Processors\Element\Category\GetList;
use MODX\Revolution\Processors\Element\Category\Remove;

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
    public function setUp() {
        parent::setUp();
        /** @var modCategory $category */
        $category = $this->modx->newObject(modCategory::class);
        $category->fromArray(['category' => 'UnitTestCategory']);
        $category->save();

        $category = $this->modx->newObject(modCategory::class);
        $category->fromArray(['category' => 'UnitTestCategory2']);
        $category->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        /** @var modCategory $category */
        $categories = $this->modx->getCollection(modCategory::class, [
            'category:LIKE' => 'UnitTest%',
        ]);
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
        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Create::class, [
            'category' => $categoryPk,
        ]);
        if (empty($result)) {
            $this->fail('Could not load '.Create::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $newCategory = $this->modx->getObject(modCategory::class, ['category' => $categoryPk]);
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
        return [
            [true,'UnitTestCat'],
            [true,'UnitTestCat2'],
            [false,'UnitTestCategory'], /* already exists */
            [false,''],
        ];
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
        $category = $this->modx->getObject(modCategory::class, ['category' => $categoryPk]);
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Get::class, [
            'id' => $category ? $category->get('id') : $categoryPk,
        ]);
        if (empty($result)) {
            $this->fail('Could not load '.Get::class.' processor');
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
        return [
            [true,'UnitTestCategory'],
            [false,234],
            [false,''],
        ];
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
        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(GetList::class, [
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ]);
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
        return [
            [true,'category','ASC',5,0],
            [true,'id','ASC',5,0],
            [true,'category','DESC',null,0],
            [false,'category','ASC',5,7],
            [false,'name','ASC',5,0], /* use invalid pk field */
        ];
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
        $category = $this->modx->getObject(modCategory::class, ['category' => $categoryPk]);
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Remove::class, [
            'id' => $category ? $category->get('id') : $categoryPk,
        ]);
        if (empty($result)) {
            $this->fail('Could not load '.Remove::class.' processor');
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
        return [
            [true,'UnitTestCategory'],
            [false,234],
            [false,''],
        ];
    }
}
