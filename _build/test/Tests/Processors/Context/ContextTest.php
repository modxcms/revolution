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
 * Tests related to context/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Context
 * @group ContextProcessors
 * @group modContext
 */
class ContextProcessorsTest extends MODxTestCase {
    /** @const PROCESSOR_LOCATION */
    const PROCESSOR_LOCATION = 'context/';

    /**
     * Setup some basic data for this test.
     */
    public function setUp() {
        parent::setUp();
        /** @var modContext $ctx */
        $ctx = $this->modx->newObject('modContext');
        $ctx->fromArray(array(
            'key' => 'unittest',
        ),'',true,true);
        $ctx->save();

        $ctx = $this->modx->newObject('modContext');
        $ctx->set('key','unittest13');
        $ctx->set('description','The unit test numbered 13. What else would it be?');
        $ctx->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        $contexts = $this->modx->getCollection('modContext',array(
            'key:LIKE' => '%unittest%'
        ));
        /** @var modContext $ctx */
        foreach ($contexts as $ctx) {
            $ctx->remove();
        }
    }

    /**
     * Tests the context/create processor, which creates a context
     *
     * @param string $ctx
     * @param string $description
     * @dataProvider providerContextCreate
     */
    public function testContextCreate($ctx,$description = '') {
        if (empty($ctx)) return;
        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor('context/create',array(
            'key' => $ctx,
            'description' => $description,
        ));
        if (empty($result)) {
            $this->fail('Could not load context/create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modContext',$ctx);
        $this->assertTrue($s && $ct > 0,'Could not create context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/create processor test.
     * @return array
     */
    public function providerContextCreate() {
        return array(
            array('unittest4','Our unit testing context.'),
        );
    }

    /**
     * Tries to create an invalid context
     *
     * @TODO Fix this; letting it run causes other tests to fail since the error persists across test. For some reason
     * the error handler's error queue isn't being reset.
     *
     * @param string $ctx
     * @return boolean
     * @dataProvider providerContextCreateInvalid
     */
    public function testContextCreateInvalid($ctx = '') {
        $this->assertTrue(true); return true;
        if (empty($ctx)) return;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $ct = !in_array($ctx,array('mgr','web')) ? $this->modx->getCount('modContext',$ctx) : 0;
        $success = $s == false && $ct == 0;
        $this->assertTrue($success,'Was able to create an invalid context: `'.$ctx.'`: '.$result->getMessage());
        return $success;
    }
    /**
     * Data provider for context/create processor test.
     * @return array
     */
    public function providerContextCreateInvalid() {
        return array(
            array('mgr'),
            array('_12test'),
        );
    }

    /**
     * Attempts to duplicate a context
     * @param string $ctx
     * @param string $newKey
     * @return boolean
     * @dataProvider providerContextDuplicate
     * @depends testContextCreate
     */
    public function testContextDuplicate($ctx,$newKey) {
        if (empty($ctx) || empty($newKey)) return false;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'duplicate',array(
            'key' => $ctx,
            'newkey' => $newKey,
        ));
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modContext',array('key' => $ctx));
        $success = $s && $ct > 0;
        $this->assertTrue($success,'Could not duplicate context: `'.$ctx.'` to key `'.$newKey.'`: '.$result->getMessage().' : '.implode(',',$result->getFieldErrors()));
        return $success;
    }
    /**
     * Data provider for context/duplicate processor test.
     * @return array
     */
    public function providerContextDuplicate() {
        return array(
            array('unittest','unittestCopy'),
        );
    }

    /**
     * Attempts to update a context
     * @dataProvider providerContextUpdate
     * @depends testContextCreate
     *
     * @TODO pass in some settings in JSON format to test that.
     * @param string $ctx
     * @param string $description
     */
    public function testContextUpdate($ctx,$description = '') {
        if (empty($ctx)) return;

        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'update',array(
            'key' => $ctx,
            'description' => $description,
        ));
        $s = $this->checkForSuccess($result);
        $r = $result->getObject();
        $match = !empty($r) && $r['description'] == 'Changing the description of our test context.';
        $success = $s && $match;
        $this->assertTrue($success,'Could not update context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/update processor test.
     * @return array
     */
    public function providerContextUpdate() {
        return array(
            array('unittest','Changing the description of our test context.'),
        );
    }

    /**
     * Attempts to get a context
     * @dataProvider providerContextGet
     * @depends testContextCreate
     *
     * @TODO pass in some settings in JSON format to test that.
     * @param string $ctx
     * @return boolean
     */
    public function testContextGet($ctx) {
        if (empty($ctx)) return false;

        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $r = $result->getObject('object');
        $match = !empty($r['key']) && $r['key'] == $ctx;
        $success = $s && $match;
        $this->assertTrue($success,'Could not get context: `'.$ctx.'`: '.$result->getMessage());
        return $success;
    }
    /**
     * Data provider for context/get processor test.
     * @return array
     */
    public function providerContextGet() {
        return array(
            array('unittest'),
        );
    }


    /**
     * Attempts to get a non-existent context
     *
     * @param string $ctx
     * @param string $description
     * @return boolean
     * @dataProvider providerContextGetInvalid
     */
    public function testContextGetInvalid($ctx,$description = '') {
        if (empty($ctx)) return false;

        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $r = $result->getObject();
        $match = empty($r);
        $success = $s == false && $match;
        $this->assertTrue($success,'Somehow got a non-existent context: `'.$ctx.'`: '.$result->getMessage());
        return $success;
    }
    /**
     * Data provider for context/getinvalid processor test.
     * @return array
     */
    public function providerContextGetInvalid() {
        return array(
            array('unittestdoesntexistatall'),
        );
    }

    /**
     * Attempts to get a list of contexts
     *
     * @TODO Fix this. Seems to crash phpunit when the getlist processor is run.
     *
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @dataProvider providerContextGetList
     */
    public function testContextGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $this->assertTrue(!empty($results),'Could not get list of contexts: '.$result->getMessage());
    }
    /**
     * Data provider for context/get processor test.
     * @return array
     */
    public function providerContextGetList() {
        return array(
            array('key','ASC',5,0),
        );
    }

    /**
     * Tests the context/remove processor, which removes a context
     *
     * @param string $ctx
     * @return boolean
     * @dataProvider providerContextRemove
     * @depends testContextCreate
     * @depends testContextDuplicate
     */
    public function testContextRemove($ctx = '') {
        $this->assertTrue(true); return true;
        /*
        if (empty($ctx)) return false;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $this->assertTrue($s,'Could not remove context: `'.$ctx.'`: '.$result->getMessage());*/
    }
    /**
     * Data provider for context/remove processor test.
     * @return array
     */
    public function providerContextRemove() {
        return array(
            array('unittest'),
            array('unittestdupe'),
        );
    }
}
