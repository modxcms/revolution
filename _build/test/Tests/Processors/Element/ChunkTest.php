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
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        $modx->error->reset();
        $modx->lexicon->load('chunk');
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk2'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk3'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'Untitled Chunk'));
        if ($chunk) $chunk->remove();

        $category = $modx->getObject('modCategory',array('category' => 'UnitTestChunks'));
        if (!$category) {
            $category = $modx->newObject('modCategory');
            $category->set('id',1);
            $category->set('category','UnitTestChunks');
            $category->save();
        }
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk2'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'UnitTestChunk3'));
        if ($chunk) $chunk->remove();
        $chunk = $modx->getObject('modChunk',array('name' => 'Untitled Chunk'));
        if ($chunk) $chunk->remove();

        $category = $modx->getObject('modCategory',array('category' => 'UnitTestChunks'));
        if ($category) $category->remove();
    }

    /**
     * Tests the element/chunk/create processor, which creates a Chunk
     * @dataProvider providerChunkCreate
     */
    public function testChunkCreate($shouldPass,$chunkPk,array $properties = array()) {
        $properties['name'] = $chunkPk;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',$properties);
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
            /* pass: straight up chunk */
            array(true,'UnitTestChunk'), 
            /* pass: another chunk with valid other fields */
            array(true,'UnitTestChunk2',array(
                'description' => '2nd Unit Testing chunk',
                'snippet' => '<p>Test</p>',
                'locked' => false,
                'category' => 1,
            )),
            /* fail: invalid category */
            /* @TODO: Fix. For some reason this crashes on the $category->checkPolicy('add_children') in the
             * create processor. (line 33)
            array(false,'UnitTestChunk3',array(
                'category' => 123,
            )),*/
            /* fail: already exists */
            array(false,'UnitTestChunk2'),
            /* fail: no data */
            array(false,''),
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
     * Attempts to update a chunk
     * @dataProvider providerChunkUpdate
     * @depends testChunkCreate
     */
    public function testChunkUpdate($shouldPass,$chunkPk,$properties) {
        $chunk = $this->modx->getObject('modChunk',array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }
        $data = $properties;
        $data['id'] = $chunk ? $chunk->get('id') : $chunkPk;
        $data['name'] = $chunkPk;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'update',$data);
        $passed = $this->checkForSuccess($result);
        if ($passed) {
            $r = $result->getObject();
            foreach ($properties as $k => $v) {
                $passed = !empty($r) && $r[$k] == $v;
            }
        }
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not update chunk: `'.$chunkPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for chunk/update processor test.
     */
    public function providerChunkUpdate() {
        return array(
            /* pass: change the description/locked */
            array(true,'UnitTestChunk',array(
                'description' => 'Changing the description of our test chunk.',
                'locked' => false,
            )),
            /* pass: change the category */
            array(true,'UnitTestChunk',array(
                'category' => 1,
            )),
            /* fail: change to invalid category */
            array(false,'UnitTestChunk',array(
                'category' => 9999,
            )),
            /* fail: no data */
            array(false,''),
            /* fail: invalid ID */
            array(false,9999),
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
            array(false,9999), /* fail: invalid ID */
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
            array(false,9999), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }
}