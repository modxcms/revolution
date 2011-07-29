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
 * Tests related to the modRequest class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Request
 * @group modRequest
 */
class modRequestTest extends MODxTestCase {
    /** @var modRequest $request */
    public $request;

    public function setUp() {
        parent::setUp();
        /** @var modNamespace $namespace */
        $namespace = $this->modx->newObject('modNamespace');
        $namespace->set('name','unit-test');
        $namespace->set('path','{core_path}');
        $namespace->save();

        /** @var modAction $action */
        $action = $this->modx->newObject('modAction');
        $action->fromArray(array(
            'namespace' => 'unit-test',
            'parent' => 0,
            'controller' => 'index',
            'haslayout' => 1,
            'lang_topics' => '',
        ));
        $action->save();


        $_POST['testPost'] = 1;
        $_GET['testGet'] = 2;
        $_COOKIE['testCookie'] = 3;
        $_REQUEST['testRequest'] = 4;
        $this->modx->loadClass('modRequest',null,true,true);
        $this->request = new modRequest($this->modx);
    }

    public function tearDown() {
        parent::tearDown();

        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject('modNamespace',array('name' => 'unit-test'));
        if ($namespace) { $namespace->remove(); }

        $actions = $this->modx->getCollection('modAction',array(
            'namespace' => 'unit-test',
        ));
        /** @var modAction $action */
        foreach ($actions as $action) {
            $action->remove();
        }
    }

    /**
     * Test to ensure modRequest is properly setting request parameters
     */
    public function testConstructorRequestParameters() {
        $this->assertArrayHasKey('testPost',$this->request->parameters['POST'],'The modRequest constructor did not set the POST parameters properly.');
        $this->assertArrayHasKey('testGet',$this->request->parameters['GET'],'The modRequest constructor did not set the GET parameters properly.');
        $this->assertArrayHasKey('testCookie',$this->request->parameters['COOKIE'],'The modRequest constructor did not set the COOKIE parameters properly.');
        $this->assertArrayHasKey('testRequest',$this->request->parameters['REQUEST'],'The modRequest constructor did not set the REQUEST parameters properly.');
    }

    /**
     * Test the getResourceMethod method for getting the proper request method
     * 
     * @param string|int $expected
     * @param string $requestKey
     * @param string|int $requestValue
     * @param string $paramAlias
     * @param string $paramId
     * @dataProvider providerGetResourceMethod
     */
    public function testGetResourceMethod($expected,$requestKey,$requestValue,$paramAlias = 'q',$paramId = 'id') {
        $_REQUEST[$requestKey] = $requestValue;
        $this->modx->setOption('request_param_alias',$paramAlias);
        $this->modx->setOption('request_param_id',$paramId);

        $method = $this->request->getResourceMethod();
        $this->assertEquals($expected,$method,'The Resource Method did not match the expected value.');
        unset($_REQUEST[$requestKey]);
    }
    /**
     * @return array
     */
    public function providerGetResourceMethod() {
        return array(
            array('id','id',123),
            array('id','idx',123,null,'idx'),
            array('alias','p',2112,'p'),
            array('alias','q','test.html'),
            array('alias','page','test.html','page'),
        );
    }

    /**
     * Test the getResourceIdentifier method
     * 
     * @param string|int $expected
     * @param string $requestKey
     * @param string|int $requestValue
     * @param string $method
     * @param string $paramAlias
     * @param string $paramId
     * @dataProvider providerGetResourceIdentifier
     */
    public function testGetResourceIdentifier($expected,$requestKey,$requestValue,$method = 'alias',$paramAlias = 'q',$paramId = 'id') {
        $_REQUEST[$requestKey] = $requestValue;
        $this->modx->setOption('request_param_alias',$paramAlias);
        $this->modx->setOption('request_param_id',$paramId);
        $this->modx->setOption('site_start',1);
        $identifier = $this->request->getResourceIdentifier($method);

        $this->assertEquals($expected,$identifier,'The Resource Identifier did not match the expected value.');
        unset($_REQUEST[$requestKey]);
    }
    /**
     * @return array
     */
    public function providerGetResourceIdentifier() {
        return array(
            array('test.html','q','test.html','alias'),
            array('test.html','qz','test.html','alias','qz'),
            array('','no','test.html','alias'),
            array(123,'id',123,'id'),
            array(123,'idx',123,'id',null,'idx'),
            array('1',null,null,''),
            array('1','qq','test.html',''),
            array('1','idx',123,''),
        );
    }

    /**
     * Test the loadErrorHandler method
     */
    public function testLoadErrorHandler() {
        $this->request->loadErrorHandler();
        $this->assertInstanceOf('modError',$this->modx->error,'modRequest.loadErrorHandler did not load a modError-derivative class!');
    }

    public function testRetrieveRequest() {
        if (empty($_SESSION)) $_SESSION = array();
        $_SESSION['modx.request.unit-test'] = $_REQUEST;
        $request = $this->request->retrieveRequest('unit-test');
        $this->assertNotEmpty($request,'modRequest.retrieveRequest did not correctly retrieve the REQUEST data.');
        $this->assertArrayHasKey('testRequest',$request,'modRequest.retrieveRequest did not retrieve the correct REQUEST data, as it does not contain a valid REQUEST field.');
        unset($_SESSION['modx.request.unit-test']);
    }

    /**
     * Ensure that the preserveRequest method properly preserves the REQUEST object
     * @return void
     * @depends testRetrieveRequest
     */
    public function testPreserveRequest() {
        if (empty($_SESSION)) $_SESSION = array();
        $this->request->preserveRequest('unit-test');
        $request = $this->request->retrieveRequest('unit-test');
        $this->assertNotEmpty($request,'modRequest.preserveRequest did not correctly preserve the REQUEST data.');
        $this->assertArrayHasKey('testRequest',$request,'modRequest.preserveRequest did not preserve the correct REQUEST data, as it does not contain a valid REQUEST field.');
        unset($_SESSION['modx.request.unit-test']);
    }

    /**
     * Test the getAllActionIDs method
     */
    public function testGetAllActionIDs() {
        $actions = $this->request->getAllActionIDs();
        $total = $this->modx->getCount('modAction');
        $this->assertTrue(count($actions) == $total,'The getAllActionIDs method did not get all of the Actions that exist.');

        $actions = $this->request->getAllActionIDs('unit-test');
        $total = $this->modx->getCount('modAction',array('namespace' => 'unit-test'));
        $this->assertTrue(count($actions) == $total,'The getAllActionIDs method did not filter down by namespace when grabbing actions.');
    }
}