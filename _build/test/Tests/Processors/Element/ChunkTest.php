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
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk3'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'Untitled Chunk'));
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
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk3'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'Untitled Chunk'));
        if ($chunk) $chunk->remove();
    }

    /**
     * Tests the element/chunk/create processor, which creates a Chunk
     * @dataProvider providerChunkCreate
     */
    public function testChunkCreate($shouldPass,$chunkPk) {
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
            array(true,'UnitTestChunk'), /* pass: default chunk */
            array(true,'UnitTestChunk2'), /* pass: another chunk */
            array(false,'UnitTestChunk2'), /* fail: already exists */
            array(false,''), /* fail: no data */
        );
    }


    /**
     * Tests the element/chunk/duplicate processor, which duplicates a Chunk
     * @dataProvider providerChunkDuplicate
     */
    public function testChunkDuplicate($shouldPass,$chunkPk,$newName) {
        $chunk = $this->modx->getObject('modChunk',array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }
        $this->modx->lexicon->load('default');

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'duplicate',array(
            'id' => $chunk ? $chunk->get('id') : $chunkPk,
            'name' => $newName,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'duplicate processor');
        }
        $s = $this->checkForSuccess($result);
        if (empty($newName) && $chunk) {
            $newName = $this->modx->lexicon('duplicate_of',array('name' => $chunk->get('name')));
        }
        $ct = $this->modx->getObject('modChunk',array('name' => $newName));
        $passed = $s && $ct;
        $passed = $shouldPass ? $passed : !$passed;
        if ($ct) { /* remove test data */
            $ct->remove();
        }
        $this->assertTrue($passed,'Could not duplicate Chunk: `'.$chunkPk.'` to `'.$newName.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/chunk/duplicate processor test.
     */
    public function providerChunkDuplicate() {
        return array(
            array(true,'UnitTestChunk','UnitTestChunk3'), /* pass: standard name */
            array(true,'UnitTestChunk',''), /* pass: with blank name */
            array(false,'',''), /* fail: no data */
            array(false,'','UnitTestChunk3'), /* fail: blank chunk to duplicate */
        );
    }

    /**
     * Tests the element/chunk/get processor, which gets a Chunk
     * @dataProvider providerChunkGet
     */
    public function testChunkGet($shouldPass,$chunkPk) {
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
            array(true,'UnitTestChunk'), /* pass: get chunk from create test */
            array(false,234), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }

    /**
     * Attempts to get a list of chunks
     *
     * @dataProvider providerChunkGetList
     */
    public function testChunkGetList($shouldPass = true,$sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getList',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $passed = !empty($results);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get list of Chunks: '.$result->getMessage());
    }
    
    /**
     * Data provider for element/chunk/getlist processor test.
     */
    public function providerChunkGetList() {
        return array(
            array(true,'name','ASC',5,0), /* pass: sort 5 by name asc */
            array(true,'name','DESC',5,0), /* pass: sort 5 by name desc */
            array(false,'name','ASC',5,5), /* fail: start beyond what exists */
            array(false,'badname','ASC',5,5), /* fail: invalid sort column */
        );
    }

    /**
     * Tests the element/chunk/remove processor, which removes a Chunk
     * @dataProvider providerChunkRemove
     */
    public function testChunkRemove($shouldPass,$chunkPk) {
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
            array(true,'UnitTestChunk'), /* pass: remove chunk from create test */
            array(false,234), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }
}