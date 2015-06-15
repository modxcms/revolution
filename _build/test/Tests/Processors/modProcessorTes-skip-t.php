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
 * Tests related to the modProcessor class.
 *
 * @TODO Refactor this to use mock class
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group modProcessor
 */
class modProcessorTest extends MODxTestCase {
    /**
     * @var modProcessor $processor
     */
    public $processor;
    const MODX_TEST_PROCESSOR = '_build/test/data/processors/demo.processor.php';

    public function setUp() {
        parent::setUp();
        $this->modx->loadClass('modProcessor',MODX_CORE_PATH.'model/modx/',true,true);
        $this->processor = new modProcessor($this->modx);
        $this->processor->setPath(MODX_BASE_PATH.self::MODX_TEST_PROCESSOR);
    }

    public function tearDown() {
        parent::tearDown();
        $this->processor = null;
    }

    /**
     * @param string $path
     * @dataProvider providerSetPath
     */
    public function testSetPath($path) {
        $this->processor->setPath(MODX_BASE_PATH.$path);
        $this->assertEquals(MODX_BASE_PATH.$path,$this->processor->path,'modProcessor did not correctly set the proper path for the processor.');

    }
    /**
     * @return array
     */
    public function providerSetPath() {
        return array(
            array(self::MODX_TEST_PROCESSOR),
        );
    }

    /**
     * @param array $properties
     * @param string $key
     * @param mixed $value
     * @dataProvider providerSetProperties
     */
    public function testSetProperties(array $properties,$key,$value) {
        $this->processor->setProperties($properties);
        $this->assertArrayHasKey($key,$this->processor->properties);
        $this->assertEquals($value,$this->processor->properties[$key],'modProcessor did not correctly set the properties.');
    }
    /**
     * @return array
     */
    public function providerSetProperties() {
        return array(
            array(array('test' => 1, 'two' => 'fun'),'two','fun'),
        );
    }

    /**
     * @param array $array
     * @param integer|boolean $count
     * @param string $expected
     * @dataProvider providerOutputArray
     */
    public function testOutputArray(array $array,$count,$expected) {
        $result = $this->processor->outputArray($array,$count);
        $this->assertEquals($expected,$result,'modProcessor->outputArray did not convert the array properly to JSON.');
    }
    /**
     * @return array
     */
    public function providerOutputArray() {
        return array(
            array(array('test' => 1),false,'({"total":"1","results":{"test":1}})'),
            array(array('test' => 1),10,'({"total":"10","results":{"test":1}})'),
        );
    }

    /**
     *
     * @param array|string $response
     * @param string $separator
     * @param string $expected
     * @dataProvider providerProcessEventResponse
     */
    public function testProcessEventResponse($response,$separator,$expected) {
        $result = $this->processor->processEventResponse($response,$separator);
        $this->assertEquals($expected,$result,'The processEventResponse did not parse the event response correctly.');
    }
    /**
     * @return array
     */
    public function providerProcessEventResponse() {
        return array(
            array('test',"\n",'test'),
            array(array('Result One','Result Two'),"\n","Result One\nResult Two"),
            array(array('Result One','Result Two'),"<br>","Result One<br>Result Two"),
        );
    }

    /**
     * Tests and ensures processors run correctly
     * 
     * @param array $properties
     * @param boolean $success
     * @param string $message
     * @param array $object
     * @dataProvider providerRun
     * @depends testSetProperties
     * @depends testSetPath
     */
    public function testRun(array $properties,$success = true,$message = '',$object = array()) {
        $this->processor->setPath(MODX_BASE_PATH.self::MODX_TEST_PROCESSOR);
        $this->processor->setProperties($properties);
        /** @var modProcessorResponse $response */
        $response = $this->processor->run();
        $this->assertInstanceOf('modProcessorResponse',$response);
        $this->assertEquals($success,$response->response['success'],'modProcessor->run did not return the proper response type: '.($success ? 'success' : 'failure'));
        if (!empty($message)) {
            $this->assertEquals($message,$response->response['message'],'modProcessor->run did not return the proper response message.');
        }
        if (!empty($object)) {
            $this->assertEquals($object,$response->response['object'],'modProcessor->run did not return the proper response object.');
        }
    }
    /**
     * @return array
     */
    public function providerRun() {
        return array(
            array(array('test' => 'one'),true,'Success!',array('id' => 123)),
            array(array('fail' => true),false,'A failure message.',array('bad' => true)),
        );
    }
}
