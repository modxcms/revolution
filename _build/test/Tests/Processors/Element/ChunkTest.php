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
 * Tests related to element/chunk/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group Chunk
 * @group ChunkProcessors
 */
class ChunkProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/chunk/';

    /**
     * Setup some basic data for this test.
     */
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $modx->error->reset();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk2'));
        if ($chunk) $chunk->remove();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk2'));
        if ($chunk) $chunk->remove();
    }

    /**
     * Tests the element/chunk/create processor, which creates a Chunk
     * @dataProvider providerChunkCreate
     */
    public function testChunkCreate($shouldPass,$chunkPk) {
        if (empty($chunkPk)) return false;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'name' => $chunkPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modChunk',array('name' => $chunkPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Chunk: `'.$chunkPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/chunk/create processor test.
     */
    public function providerChunkCreate() {
        return array(
            array(true,'UnitTestChunk'),
            array(true,'UnitTestChunk2'),
            array(false,'UnitTestChunk2'),
        );
    }

    /**
     * Tests the element/chunk/get processor, which gets a Chunk
     * @dataProvider providerChunkGet
     */
    public function testChunkGet($shouldPass,$chunkPk) {
        if (empty($chunkPk)) return false;

        $chunk = $this->modx->getObject('modChunk',array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $chunk ? $chunk->get('id') : $chunkPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Chunk: `'.$chunkPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/chunk/create processor test.
     */
    public function providerChunkGet() {
        return array(
            array(true,'UnitTestChunk'),
            array(false,234),
        );
    }

    /**
     * Attempts to get a list of chunks
     *
     * @TODO Fix this. Seems to crash phpunit when the getlist processor is run.
     *
     * @dataProvider providerChunkGetList
     */
    public function testChunkGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getList',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $this->assertTrue(!empty($results),'Could not get list of Chunks: '.$result->getMessage());
    }
    
    /**
     * Data provider for element/chunk/getlist processor test.
     */
    public function providerChunkGetList() {
        return array(
            array('name','ASC',5,0),
        );
    }

    /**
     * Tests the element/chunk/remove processor, which removes a Chunk
     * @dataProvider providerChunkRemove
     */
    public function testChunkRemove($shouldPass,$chunkPk) {
        if (empty($chunkPk)) return false;

        $chunk = $this->modx->getObject('modChunk',array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $chunk ? $chunk->get('id') : $chunkPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Chunk: `'.$chunkPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/chunk/remove processor test.
     */
    public function providerChunkRemove() {
        return array(
            array(true,'UnitTestChunk'),
            array(false,234),
        );
    }
}