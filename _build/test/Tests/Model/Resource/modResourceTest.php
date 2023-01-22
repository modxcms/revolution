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

namespace MODX\Revolution\Tests\Model\Resource;


use MODX\Revolution\modDocument;
use MODX\Revolution\modResource;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modResource class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Resource
 * @group modResource
 */
class modResourceTest extends MODxTestCase
{
    /**
     * @var modResource $resource
     */
    protected $resource;

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        /** @var modResource $resource */
        $resource = $this->modx->newObject(modResource::class);
        $resource->fromArray([
            'id' => 12345,
            'pagetitle' => 'Unit Test Resource',
            'type' => 'document',
            'longtitle' => '',
            'description' => '',
            'alias' => 'unit-test',
            'published' => true,
            'parent' => 1234,
            'isfolder' => true,
            'menuindex' => 99999,
            'content' => '<h2>A Unit Test Resource [[*id]]</h2>',
            'template' => 0,
            'searchable' => true,
            'cacheable' => true,
            'deleted' => false,
            'menutitle' => 'Unit Test Resource',
            'hidemenu' => false,
            'class_key' => modDocument::class,
            'context_key' => 'web',
            'content_type' => 1,
        ], '', true, true);
        $resource->save();

        $this->resource = $resource;
    }

    /**
     * Tear down fixtures after each test.
     *
     * @after
     */
    public function tearDownFixtures()
    {
        parent::tearDownFixtures();
        $this->resource->remove();
    }

    public function testGetContent()
    {
        $content = $this->resource->getContent();
        $this->assertEquals('<h2>A Unit Test Resource [[*id]]</h2>', $content);
    }

    public function testParseContent()
    {
        $parsedContent = $this->resource->parseContent();
        $this->assertEquals('<h2>A Unit Test Resource 12345</h2>', $parsedContent);
    }

    public function testParseContentWithPlaceholders()
    {
        $this->resource->setContent('Placeholder: [[+foo]]');

        $parsedContent = $this->resource->parseContent([
            'foo' => 'bar',
        ]);
        $this->assertEquals('Placeholder: bar', $parsedContent);
    }

    public function testLock()
    {
        $locked = $this->resource->addLock();
        $this->assertTrue($locked);
        $lock = $this->resource->getLock();
        $this->assertGreaterThan(0, $lock);

        $lockRemoved = $this->resource->removeLock();
        $this->assertTrue($lockRemoved);
        $lock = $this->resource->getLock();
        $this->assertEquals(0, $lock);
    }

    public function testHasChildren()
    {
        $numberOfChildren = $this->resource->hasChildren();
        $this->assertEquals(0, $numberOfChildren);

        $parentResource = $this->modx->newObject(modResource::class);
        $parentResource->set('id', 1234);
        $numberOfChildren = $parentResource->hasChildren();
        $this->assertEquals(1, $numberOfChildren);
    }
}
