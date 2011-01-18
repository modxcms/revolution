<?php
/**
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
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
 * Tests related to element/snippet/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group Snippet
 * @group SnippetProcessors
 */
class SnippetProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/snippet/';

    /**
     * Setup some basic data for this test.
     */
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $modx->error->reset();
        $snippet = $modx->getObject('modSnippet',array('name' => 'UnitTestSnippet'));
        if ($snippet) $snippet->remove();
        $snippet = $modx->getObject('modSnippet',array('name' => 'UnitTestSnippet2'));
        if ($snippet) $snippet->remove();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $snippet = $modx->getObject('modSnippet',array('name' => 'UnitTestSnippet'));
        if ($snippet) $snippet->remove();
        $snippet = $modx->getObject('modSnippet',array('name' => 'UnitTestSnippet2'));
        if ($snippet) $snippet->remove();
    }

    /**
     * Tests the element/snippet/create processor, which creates a Snippet
     * @dataProvider providerSnippetCreate
     */
    public function testSnippetCreate($shouldPass,$snippetPk) {
        if (empty($snippetPk)) return false;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'name' => $snippetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modSnippet',array('name' => $snippetPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Snippet: `'.$snippetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/snippet/create processor test.
     */
    public function providerSnippetCreate() {
        return array(
            array(true,'UnitTestSnippet'),
            array(true,'UnitTestSnippet2'),
            array(false,'UnitTestSnippet2'),
        );
    }

    /**
     * Tests the element/snippet/get processor, which gets a Snippet
     * @dataProvider providerSnippetGet
     */
    public function testSnippetGet($shouldPass,$snippetPk) {
        if (empty($snippetPk)) return false;

        $snippet = $this->modx->getObject('modSnippet',array('name' => $snippetPk));
        if (empty($snippet) && $shouldPass) {
            $this->fail('No Snippet found "'.$snippetPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $snippet ? $snippet->get('id') : $snippetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Snippet: `'.$snippetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/snippet/create processor test.
     */
    public function providerSnippetGet() {
        return array(
            array(true,'UnitTestSnippet'),
            array(false,234),
        );
    }

    /**
     * Tests the element/snippet/remove processor, which removes a Snippet
     * @dataProvider providerSnippetRemove
     */
    public function testSnippetRemove($shouldPass,$snippetPk) {
        if (empty($snippetPk)) return false;

        $snippet = $this->modx->getObject('modSnippet',array('name' => $snippetPk));
        if (empty($snippet) && $shouldPass) {
            $this->fail('No Snippet found "'.$snippetPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $snippet ? $snippet->get('id') : $snippetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Snippet: `'.$snippetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/snippet/remove processor test.
     */
    public function providerSnippetRemove() {
        return array(
            array(true,'UnitTestSnippet'),
            array(false,234),
        );
    }
}