<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 */
namespace MODX\Revolution\Tests\Cases\Request;

use MODX\Revolution\modResource;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to verifying and setting up the test environment.
 *
 * @package modx-test
 * @subpackage modx
 * @group Cases
 * @group Request
 * @group MakeUrl
 */
class MakeUrlTest extends MODxTestCase {
    public function setUp() {
        parent::setUp();

        /** @var modResource $resource */
        $resource = $this->modx->newObject(modResource::class);
        $resource->fromArray(array(
            'id' => 12345,
            'pagetitle' => 'Unit Test Resource',
            'type' => 'document',
            'contentType' => 1,
            'longtitle' => '',
            'description' => '',
            'alias' => 'unit-test',
            'published' => true,
            'parent' => 0,
            'isfolder' => true,
            'menuindex' => 99999,
            'content' => '<h2>A Unit Test Resource</h2>',
            'template' => 0,
            'searchable' => true,
            'cacheable' => true,
            'deleted' => false,
            'menutitle' => 'Unit Test Resource',
            'hidemenu' => false,
            'class_key' => 'modDocument',
            'context_key' => 'web',
            'content_type' => 1,
        ),'',true,true);
        $resource->save();

        $resource = $this->modx->newObject(modResource::class);
        $resource->fromArray(array(
            'id' => 12346,
            'parent' => 12345,
            'pagetitle' => 'Unit Test Child Resource',
            'type' => 'document',
            'contentType' => 1,
            'longtitle' => '',
            'description' => '',
            'alias' => 'child',
            'published' => true,
            'isfolder' => false,
            'menuindex' => 99999,
            'content' => '<h2>A Unit Test Child Resource</h2>',
            'template' => 0,
            'searchable' => true,
            'cacheable' => true,
            'deleted' => false,
            'menutitle' => 'Unit Test Child Resource',
            'hidemenu' => false,
            'class_key' => 'modDocument',
            'context_key' => 'web',
            'content_type' => 1,
        ),'',true,true);
        $resource->save();

        $this->modx->setOption('friendly_urls', true);
        $this->modx->setOption('automatic_alias', true);
        $this->modx->setOption('use_alias_path', true);
        $this->modx->setOption('cache_alias_map', false);
        //$this->modx->context->prepare(true);
        $this->modx->context->aliasMap = null;
    }
    public function tearDown() {
        parent::tearDown();
        /** @var modResource $resource */
        $resource = $this->modx->getObject(modResource::class,array('pagetitle' => 'Unit Test Resource'));
        if ($resource) $resource->remove();
        $resource = $this->modx->getObject(modResource::class,array('pagetitle' => 'Unit Test Child Resource'));
        if ($resource) $resource->remove();
    }

    /**
     * Test a single call to makeUrl with the base Resource and no parameters
     *
     * @param int $id
     * @param string $expected
     * @dataProvider providerSingleParameter
     */
    public function testSingleParameter($id,$expected) {
        $url = $this->modx->makeUrl($id);
        $this->assertEquals($expected, $url);
    }
    /**
     * @return array
     */
    public function providerSingleParameter() {
        return array(
            // Dummy data to pass on first makeUrl
            array(12345, ''),
            array(12345, 'unit-test/'),
            array(12346, 'unit-test/child.html'),
        );
    }

    /**
     * Test a call to makeUrl with REQUEST arguments
     * @param int $id
     * @param array $arguments
     * @param string $expected
     * @param boolean $xhtmlUrls
     * @dataProvider providerArguments
     * @depends testSingleParameter
     */
    public function testArguments($id,array $arguments,$expected,$xhtmlUrls = false) {
        $this->modx->setOption('xhtml_urls',$xhtmlUrls);
        $url = $this->modx->makeUrl($id,'',$arguments);
        $this->assertEquals($expected,$url);
    }
    /**
     * @return array
     */
    public function providerArguments() {
        return array(
            array(12345,array(),'unit-test/'),
            array(12345,array('one' => 1),'unit-test/?one=1'),
            array(12345,array('one' => 1,'two' => 2),'unit-test/?one=1&two=2'),
            array(12345,array('one' => 1,'two' => 2),'unit-test/?one=1&amp;two=2',true),
        );
    }

    /**
     * Test a call to makeUrl with REQUEST arguments
     * @param int $id
     * @param string $scheme
     * @param string $expected
     * @dataProvider providerScheme
     * @depends testSingleParameter
     */
    public function testScheme($id,$scheme,$expected) {
        $url = $this->modx->makeUrl($id,'',null,$scheme);
        $this->assertEquals($expected,$url);
    }
    /**
     * @return array
     */
    public function providerScheme() {
        return array(
            array(12345,'','unit-test/'),
            array(12345,'abs','/unit-test/'),
            array(12345,'full','http://unit.modx.com/unit-test/'),
            array(12345,'http','http://unit.modx.com/unit-test/'),
            array(12345,'https','https://unit.modx.com/unit-test/'),
        );
    }
}
