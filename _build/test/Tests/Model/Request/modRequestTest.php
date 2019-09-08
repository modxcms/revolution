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
namespace MODX\Revolution\Tests\Model\Request;


use MODX\Revolution\Error\modError;
use MODX\Revolution\modAction;
use MODX\Revolution\modNamespace;
use MODX\Revolution\modRequest;
use MODX\Revolution\MODxTestCase;

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
        $namespace = $this->modx->newObject(modNamespace::class);
        $namespace->set('name','unit-test');
        $namespace->set('path','{core_path}');
        $namespace->save();

        /** @var modAction $action */
        $action = $this->modx->newObject(modAction::class);
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
        $this->request = new modRequest($this->modx);
    }

    /**
     * @return void
     */
    public function tearDown() {
        parent::tearDown();

        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject(modNamespace::class,array('name' => 'unit-test'));
        if ($namespace) { $namespace->remove(); }

        $actions = $this->modx->getCollection(modAction::class,array(
            'namespace' => 'unit-test',
        ));
        /** @var modAction $action */
        foreach ($actions as $action) {
            $action->remove();
        }
        $this->modx->setOption('request_param_alias','q');
        $this->modx->setOption('request_param_id','id');
        $this->modx->setOption('site_start',1);
        $this->modx->setOption('friendly_urls',true);
        $this->modx->setOption('container_suffix','/');

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
        $this->assertInstanceOf(modError::class, $this->modx->error,'modRequest.loadErrorHandler did not load a modError-derivative class!');
    }

    /**
     * Ensure that the retrieveRequest method properly gets the stored REQUEST object
     * @return void
     */
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
        // @todo : refactor to take care of modAction deprecation
//        $actions = $this->request->getAllActionIDs();
//        $total = $this->modx->getCount('modAction');
//        $this->assertTrue(count($actions) == $total,'The getAllActionIDs method did not get all of the Actions that exist.');

        $actions = $this->request->getAllActionIDs('unit-test');
        $total = $this->modx->getCount('modAction',array('namespace' => 'unit-test'));
        $this->assertTrue(count($actions) == $total,'The getAllActionIDs method did not filter down by namespace when grabbing actions.');
    }

    /**
     * Test the getParameters method, getting various types of request data, and asking for specific keys
     */
    public function testGetParameters() {
        $parameters = $this->request->getParameters();
        $this->assertEquals(2,$parameters['testGet']);
        $parameters = $this->request->getParameters(array(),'POST');
        $this->assertEquals(1,$parameters['testPost']);
        $parameters = $this->request->getParameters(array(),'COOKIE');
        $this->assertEquals(3,$parameters['testCookie']);
        $parameters = $this->request->getParameters(array(),'REQUEST');
        $this->assertEquals(4,$parameters['testRequest']);

        $parameters = $this->request->getParameters(array('testRequest'),'REQUEST');
        $this->assertEquals(4,$parameters['testRequest']);

        $parameters = $this->request->getParameters(array('testShouldNotExist'),'REQUEST');
        $this->assertEmpty($parameters);
    }

    /**
     * Test that getClientIp properly returns possible values for the user's IP address, obtained in different ways
     * due to proxy considerations.
     *
     * @param string $ip
     * @param string $key
     * @dataProvider providerGetClientIp
     */
    public function testGetClientIp($ip,$key = 'REMOTE_ADDR') {
        $_SERVER[$key] = $ip;
        $ipArray = $this->request->getClientIp();

        $this->assertEquals($ip,$ipArray['ip']);
        unset($_SERVER[$key]);
    }
    /**
     * @return array
     */
    public function providerGetClientIp() {
        return array(
            array('123.45.67.100','REMOTE_ADDR'),
            array('123.45.67.100','HTTP_X_FORWARDED_FOR'),
            array('123.45.67.100','HTTP_X_FORWARDED'),
            array('123.45.67.100','HTTP_X_CLUSTER_CLIENT_IP'),
            array('123.45.67.100','HTTP_X_COMING_FROM'),
            array('123.45.67.100','HTTP_FORWARDED_FOR'),
            array('123.45.67.100','HTTP_FORWARDED'),
            array('123.45.67.100','HTTP_COMING_FROM'),
            array('123.45.67.100','HTTP_CLIENT_IP'),
            array('123.45.67.100','HTTP_FROM'),
            array('123.45.67.100','HTTP_VIA'),
        );
    }

    /**
     * Tests the _cleanResourceIdentifier method
     * @param string $identifier
     * @param string $expected
     * @param boolean $furls
     * @dataProvider providerCleanResourceIdentifier
     */
    public function testCleanResourceIdentifier($identifier,$expected,$furls = true) {
        $this->modx->aliasMap[$identifier] = 998;
        $this->modx->resourceMethod = 'alias';
        $this->modx->setOption('friendly_urls',$furls);
        $this->modx->setOption('container_suffix','');

        $identifier = $this->request->_cleanResourceIdentifier($identifier);
        $this->assertEquals($expected,$this->modx->resourceMethod);
        unset($this->modx->aliasMap[$identifier]);
    }
    /**
     * @return array
     */
    public function providerCleanResourceIdentifier() {
        return array(
            array('test.html','alias',true),
            array('the-cake-is-a-lie.png','alias',true),
            array('fail.html','id',false),
            array('','id'),
        );
    }

    /**
     * @param $value
     * @param $expected
     * @dataProvider providerSanitizeRequest
     */
    public function testSanitizeRequest($value,$expected) {
	    $this->modx->setOption('allow_tags_in_post',false);
        $_GET['test'] = $value;
        $_POST['test'] = $value;
        $_REQUEST['test'] = $value;
        $_COOKIE['test'] = $value;
        $this->request->sanitizeRequest();
        $this->assertEquals($expected,$_GET['test'],'Failed on GET');
        $this->assertEquals($expected,$_POST['test'],'Failed on POST');
        $this->assertEquals($expected,$_REQUEST['test'],'Failed on REQUEST');
        $this->assertEquals($expected,$_COOKIE['test'],'Failed on COOKIE');
    }
    /**
     * @return array
     */
    public function providerSanitizeRequest() {
        return array(
            array('A test string','A test string'),
            array('MODX [[fakeSnippet]] Tags','MODX  Tags'),
            array("MODX [[\$chunk? &property=`test`\n &across=`lines

` &test=1]] Tags",'MODX  Tags'),
            array('Javascript! <script>alert(\'test\');</script> Yay.','Javascript!  Yay.'),
            array("Javascript line break! <script>alert('test');\n</script>Yay.","Javascript line break! Yay."),
            array('Testing entities &#123;kthx','Testing entities kthx'),
        );
    }
}
