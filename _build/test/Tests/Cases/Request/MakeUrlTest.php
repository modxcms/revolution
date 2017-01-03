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

namespace modX\Tests\Cases\Request;

use modX\Tests\MODxTestCase;

class MakeUrlTest extends MODxTestCase {

    public function setUp() {
        parent::setUp();

        $resource = $this->modx->newObject('modResource');
        $resource->fromArray([
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
        ],'', true, true);
        $resource->save();

        $resource = $this->modx->newObject('modResource');
        $resource->fromArray([
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
        ], '', true, true);
        $resource->save();

        $this->modx->setOption('friendly_urls', true);
        $this->modx->setOption('automatic_alias', true);
        $this->modx->setOption('use_alias_path', true);
        $this->modx->setOption('cache_alias_map', false);
        $this->modx->context->aliasMap = null;
    }

    public function tearDown() {
        parent::tearDown();

        $this->modx->removeCollection('modResource');
    }

    public function testMakeUrlNonExistingResourceId() {
        $url = $this->modx->makeUrl(99999999);
        $this->assertEquals('', $url, 'Friendly URL for non existing resource should be empty string.');
    }

    public function testMakeUrlForResourceId() {
        $url = $this->modx->makeUrl(12345);
        $this->assertEquals('unit-test/', $url, 'Friendly URL did not match expected result.');
    }

    public function testMakeUrlForChildResourceId() {
        $url = $this->modx->makeUrl(12346);
        $this->assertEquals('unit-test/child.html', $url, 'Friendly URL did not match expected result.');
    }

    public function testMakeUrlWithArguments() {
        $url = $this->modx->makeUrl(12345, '', ['foo' => 'bar']);
        $this->assertEquals('unit-test/?foo=bar', $url, 'Friendly URL with argument did not match.');
    }

    public function testMakeUrlWithMultipleArguments() {
        $this->modx->setOption('xhtml_urls', false);

        $url = $this->modx->makeUrl(12345, '', [
            'foo' => 'bar',
            'bat' => 'baz',
        ]);
        $this->assertEquals('unit-test/?foo=bar&bat=baz', $url, 'Friendly URL with argument did not match.');
    }

    public function testMakeUrlWithMultipleArgumentsEncoded() {
        $this->modx->setOption('xhtml_urls', true);

        $url = $this->modx->makeUrl(12345, '', [
            'foo' => 'bar',
            'bat' => 'baz',
        ]);
        $this->assertEquals('unit-test/?foo=bar&amp;bat=baz', $url, 'Friendly URL with argument did not match.');
    }

    public function testMakeUrlAbsSchema() {
        $url = $this->modx->makeUrl(12345, '', null, 'abs');
        $this->assertEquals('/unit-test/', $url);
    }

    public function testMakeUrlFullSchema() {
        $url = $this->modx->makeUrl(12345, '', null, 'full');
        $this->assertEquals('http://unit.modx.com/unit-test/', $url);
    }

    public function testMakeUrlHTTPSchema() {
        $url = $this->modx->makeUrl(12345, '', null, 'http');
        $this->assertEquals('http://unit.modx.com/unit-test/', $url);
    }

    public function testMakeUrlHTTPSSchema() {
        $url = $this->modx->makeUrl(12345, '', null, 'https');
        $this->assertEquals('https://unit.modx.com/unit-test/', $url);
    }
}
