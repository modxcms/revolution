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
 * Tests related to context/ processors
 *
 * @package modx-test
 * @subpackage modx
 */
class ContextProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'context/';

    /**
     * Setup some basic data for this test.
     */
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $ctx = $modx->getObject('modContext','unittest');
        if ($ctx) $ctx->remove();
        $ctx = $modx->getObject('modContext','unittestdupe');
        if ($ctx) $ctx->remove();
        $ctx = $modx->getObject('modContext','unittest13');
        if ($ctx) $ctx->remove();
        $ctx = $modx->newObject('modContext');
        $ctx->set('key','unittest13');
        $ctx->set('description','The unit test numbered 13. What else would it be?');
        $ctx->save();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $ctx = $modx->getObject('modContext','unittest');
        if ($ctx) $ctx->remove();
        $ctx = $modx->getObject('modContext','unittestdupe');
        if ($ctx) $ctx->remove();
        $ctx = $modx->getObject('modContext','unittest13');
        if ($ctx) $ctx->remove();
    }

    /**
     * Tests the context/create processor, which creates a context
     * @dataProvider providerContextCreate
     */
    public function testContextCreate($ctx,$description = '') {
        if (empty($ctx)) return false;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'key' => $ctx,
            'description' => $description,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modContext',$ctx);
        $this->assertTrue($s && $ct > 0,'Could not create context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/create processor test.
     */
    public function providerContextCreate() {
        return array(
            array('unittest','Our unit testing context.'),
        );
    }

    /**
     * Tries to create an invalid context
     * @dataProvider providerContextCreateInvalid
     */
    public function testContextCreateInvalid($ctx = '') {
        if (empty($ctx)) return false;


        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $ct = !in_array($ctx,array('mgr','web')) ? $this->modx->getCount('modContext',$ctx) : 0;
        $this->assertTrue($s == false && $ct == 0,'Was able to create an invalid context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/create processor test.
     */
    public function providerContextCreateInvalid() {
        return array(
            array('mgr'),
            array('_12test'),
        );
    }

    /**
     * Attempts to duplicate a context
     * @dataProvider providerContextDuplicate
     * @depends testContextCreate
     */
    public function testContextDuplicate($ctx,$newKey) {
        if (empty($ctx) || empty($newKey)) return false;

        $this->modx->error->reset();
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'duplicate',array(
            'key' => $ctx,
            'newkey' => $newKey,
        ));
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modContext',$ctx);
        $this->assertTrue($s && $ct > 0,'Could not duplicate context: `'.$ctx.'` to key `'.$newKey.'`: '.$result->getMessage().' : '.implode(',',$result->getFieldErrors()));
    }
    /**
     * Data provider for context/duplicate processor test.
     */
    public function providerContextDuplicate() {
        return array(
            array('unittest','unittestdupe'),
        );
    }

    /**
     * Attempts to update a context
     * @dataProvider providerContextUpdate
     * @depends testContextCreate
     *
     * @TODO pass in some settings in JSON format to test that.
     */
    public function testContextUpdate($ctx,$description = '') {
        if (empty($ctx)) return false;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'update',array(
            'key' => $ctx,
            'description' => $description,
        ));
        $s = $this->checkForSuccess($result);
        $match = $result['object']['description'] == 'Changing the description of our test context.';
        $this->assertTrue($s && $match,'Could not update context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/update processor test.
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
     */
    public function testContextGet($ctx) {
        if (empty($ctx)) return false;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $r = $result->getObject('object');
        $match = $r['key'] == $ctx;
        $this->assertTrue($s && $match,'Could not get context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/get processor test.
     */
    public function providerContextGet() {
        return array(
            array('unittest'),
        );
    }


    /**
     * Attempts to get a non-existent context
     * @dataProvider providerContextGetInvalid
     */
    public function testContextGetInvalid($ctx,$description = '') {
        if (empty($ctx)) return false;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $match = empty($result['object']);
        $this->assertTrue($s == false && $match,'Somehow got a non-existent context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/getinvalid processor test.
     */
    public function providerContextGetInvalid() {
        return array(
            array('unittestdoesntexistatall'),
        );
    }

    /**
     * Attempts to get a list of contexts
     * @dataProvider providerContextGetList
     */
    public function testContextGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getList',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        if (!is_array($result)) $result = $this->modx->fromJSON($result);       
        $this->assertTrue(!empty($result),'Could not get list of contexts: '.$result->getMessage());
    }
    /**
     * Data provider for context/get processor test.
     */
    public function providerContextGetList() {
        return array(
            array('key','ASC',5,0),
        );
    }

    /**
     * Tests the context/remove processor, which removes a context
     * @dataProvider providerContextRemove
     * @depends testContextCreate
     * @depends testContextDuplicate
     */
    public function testContextRemove($ctx = '') {
        if (empty($ctx)) return false;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'key' => $ctx,
        ));
        $s = $this->checkForSuccess($result);
        $this->assertTrue($s,'Could not remove context: `'.$ctx.'`: '.$result->getMessage());
    }
    /**
     * Data provider for context/remove processor test.
     */
    public function providerContextRemove() {
        return array(
            array('unittest'),
            array('unittestdupe'),
        );
    }
}