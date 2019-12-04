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
use MODX\Revolution\modChunk;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Element\Chunk\Create;
use MODX\Revolution\Processors\Element\Chunk\Duplicate;
use MODX\Revolution\Processors\Element\Chunk\Get;
use MODX\Revolution\Processors\Element\Chunk\GetList;
use MODX\Revolution\Processors\Element\Chunk\Remove;
use MODX\Revolution\Processors\Element\Chunk\Update;

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
    public function setUp() {
        parent::setUp();
        $this->modx->lexicon->load('chunk');
        /** @var modChunk $chunk */
        $chunk = $this->modx->newObject(modChunk::class);
        $chunk->fromArray(array('name' => 'UnitTestChunk'));
        $chunk->save();

        /** @var modCategory $category */
        $category = $this->modx->newObject(modCategory::class);
        $category->set('id',1);
        $category->set('category','UnitTestChunks');
        $category->save();
    }

    /**
     * Cleanup data after each test.
     */
    public function tearDown() {
        parent::tearDown();
        $chunks = $this->modx->getCollection(modChunk::class,array('name:LIKE' => '%UnitTest%'));
        /** @var modChunk $chunk */
        foreach ($chunks as $chunk) {
            $chunk->remove();
        }

        /** @var modCategory $category */
        $category = $this->modx->getObject(modCategory::class,array('category' => 'UnitTestChunks'));
        if ($category) {
            $category->remove();
        }
        $this->modx->error->reset();
    }

    /**
     * Tests the element/chunk/create processor, which creates a Chunk
     *
     * @param boolean $shouldPass
     * @param string $chunkPk
     * @param array $properties
     * @dataProvider providerChunkCreate
     */
    public function testChunkCreate($shouldPass,$chunkPk,array $properties = array()) {
        $properties['name'] = $chunkPk;
        $result = $this->modx->runProcessor(Create::class,$properties);
        if (empty($result)) {
            $this->fail('Could not load '.Create::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount(modChunk::class,array('name' => $chunkPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Chunk: `'.$chunkPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/chunk/create processor test.
     * @return array
     */
    public function providerChunkCreate() {
        return array(
            /* pass: straight up chunk */
            array(true,'UnitTestChunk2'),
            /* pass: another chunk with valid other fields */
            array(true,'UnitTestChunk3',array(
                'description' => '2nd Unit Testing chunk',
                'snippet' => '<p>Test</p>',
                'locked' => false,
                'category' => 1,
            )),
            /* fail: invalid category */
            array(false,'UnitTestChunk3',array(
                'category' => 123,
            )),
            /* fail: already exists */
            array(false,'UnitTestChunk'),
            /* fail: no data */
            array(false,''),
        );
    }


    /**
     * Tests the element/chunk/duplicate processor, which duplicates a Chunk
     *
     * @param boolean $shouldPass
     * @param string $chunkPk
     * @param string $newName
     * @return boolean
     * @dataProvider providerChunkDuplicate
     */
    public function testChunkDuplicate($shouldPass,$chunkPk,$newName) {
        /** @var modChunk $chunk */
        $chunk = $this->modx->getObject(modChunk::class,array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }
        $this->modx->lexicon->load('default');

        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Duplicate::class,array(
            'id' => $chunk ? $chunk->get('id') : $chunkPk,
            'name' => $newName,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Duplicate::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        if (empty($newName) && $chunk) {
            $newName = $this->modx->lexicon('duplicate_of',array('name' => $chunk->get('name')));
        }
        /** @var modChunk $ct */
        $ct = $this->modx->getObject(modChunk::class,array('name' => $newName));
        $passed = $s && $ct;
        $passed = $shouldPass ? $passed : !$passed;
        if ($ct) { /* remove test data */
            $ct->remove();
        }
        $this->assertTrue($passed,'Could not duplicate Chunk: `'.$chunkPk.'` to `'.$newName.'`: '.$result->getMessage());
        return $passed;
    }
    /**
     * Data provider for element/chunk/duplicate processor test.
     * @return array
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
     *
     * @param boolean $shouldPass
     * @param string $chunkPk
     * @param array $properties
     * @return boolean
     * @dataProvider providerChunkUpdate
     * @depends testChunkCreate
     */
    public function testChunkUpdate($shouldPass,$chunkPk,array $properties = array()) {
        /** @var modChunk $chunk */
        $chunk = $this->modx->getObject(modChunk::class,array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }
        $data = $properties;
        $data['id'] = $chunk ? $chunk->get('id') : $chunkPk;
        $data['name'] = $chunkPk;

        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Update::class,$data);
        $passed = $this->checkForSuccess($result);
        if ($passed) {
            $r = $result->getObject();
            foreach ($properties as $k => $v) {
                $passed = !empty($r) && $r[$k] == $v;
            }
        }
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not update chunk: `'.$chunkPk.'`: '.$result->getMessage());
        return $passed;
    }
    /**
     * Data provider for chunk/update processor test.
     * @return array
     */
    public function providerChunkUpdate() {
        return array(
            /* pass: change the description/locked */
            array(true,'UnitTestChunk',array(
                'name' => 'UnitTestChunk',
                'description' => 'Changing the description of our test chunk.',
                'locked' => false,
            )),
            /* pass: change the category */
            array(true,'UnitTestChunk',array(
                'name' => 'UnitTestChunk',
                'category' => 1,
            )),
            /* fail: change to invalid category */
            array(false,'UnitTestChunk',array(
                'name' => 'UnitTestChunk',
                'category' => 9999,
            )),
            /* fail: no data */
            array(false,'',array(
                'name' => 'UnitTestChunk',
            )),
            /* fail: invalid ID */
            array(false,9999,array(
                'name' => 'UnitTestChunk',
            )),
        );
    }


    /**
     * Tests the element/chunk/get processor, which gets a Chunk
     * @dataProvider providerChunkGet
     */
    public function testChunkGet($shouldPass,$chunkPk) {
        $chunk = $this->modx->getObject(modChunk::class,array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(Get::class,array(
            'id' => $chunk ? $chunk->get('id') : $chunkPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Get::class.' processor');
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
        $result = $this->modx->runProcessor(GetList::class,array(
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
        $chunk = $this->modx->getObject(modChunk::class,array('name' => $chunkPk));
        if (empty($chunk) && $shouldPass) {
            $this->fail('No Chunk found "'.$chunkPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(Remove::class,array(
            'id' => $chunk ? $chunk->get('id') : $chunkPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Remove::class.' processor');
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
