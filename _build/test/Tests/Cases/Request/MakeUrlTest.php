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
        $resource = $this->modx->newObject('modResource');
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

        $resource = $this->modx->newObject('modResource');
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
        $resource = $this->modx->getObject('modResource',array('pagetitle' => 'Unit Test Resource'));
        if ($resource) $resource->remove();
        $resource = $this->modx->getObject('modResource',array('pagetitle' => 'Unit Test Child Resource'));
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
