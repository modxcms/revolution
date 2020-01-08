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
namespace MODX\Revolution\Tests\Model\Error;


use MODX\Revolution\Error\modError;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modError class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Error
 * @group modError
 */
class modErrorTest extends MODxTestCase {
    /** @var modError $error */
    public $error;

    public function setUp() {
        parent::setUp();
        $this->error = $this->modx->getService('error','error.modError');
    }

    /**
     * Ensure that the error class is reset on each load
     */
    public function tearDown() {
        parent::tearDown();
        $this->modx->services['error'] = null;
        $this->modx->error = null;
    }

    /**
     * Test the addError method and ensure it adds the correct error
     *
     * @param string $errorMsg The error message to test with
     * @dataProvider providerTestAddError
     */
    public function testAddError($errorMsg) {
        $this->error->addError($errorMsg);
        $this->assertTrue($this->error->errors[0] == $errorMsg,'modError.addError failed to insert the correct error.');
    }
    public function providerTestAddError() {
        return [
            ['A test error'],
            [''], /* should this work? does now... */
        ];
    }

    /**
     * Ensures addField adds the correct message to the proper field
     *
     * @param string $field
     * @param string $message
     * @dataProvider providerTestAddField
     */
    public function testAddField($field,$message) {
        $this->error->addField($field,$message);
        $this->assertEquals($field,$this->error->errors[0]['id'],'modError.addField failed to insert the correct error field.');
        $this->assertEquals($message,$this->error->errors[0]['msg'],'modError.addField failed to insert the correct error message.');
    }
    public function providerTestAddField() {
        return [
            ['name','Please enter a valid name.'],
            ['score',0],
            ['empty',''],
        ];
    }

    /**
     * Tests the reset method to ensure that it fully resets the error object
     * @depends testAddError
     */
    public function testReset() {
        $this->error->addError('Error to be emptied');
        $this->error->message = 'Fail';
        $this->error->status = false;
        $this->error->total = 1;
        $this->error->reset();

        $this->assertTrue(empty($this->error->errors),'The errors array was not emptied by modError.reset().');
        $this->assertTrue(empty($this->error->message),'The message var was not emptied by modError.reset().');
        $this->assertTrue(empty($this->error->total),'The total var was not emptied by modError.reset().');
        $this->assertTrue(!empty($this->error->status),'The status var was not emptied by modError.reset().');
    }

    /**
     * Tests modError.hasError, ensuring it correctly calculates when an error is added
     * @depends testAddError
     * @depends testReset
     */
    public function testHasError() {
        $this->error->addError('A test error');
        $this->assertTrue($this->error->hasError());

        $this->error->reset();
        $this->error->addField('name','A name is required.');
        $this->assertTrue($this->error->hasError());
    }

    /**
     * Ensure modError.isFieldError properly checks for a field-based error
     * @param array $error
     * @param boolean $shouldPass
     * @dataProvider providerTestIsFieldError
     */
    public function testIsFieldError($error,$shouldPass = true) {
        $passed = $this->error->isFieldError($error);
        $this->assertEquals($shouldPass,$passed);
    }
    public function providerTestIsFieldError() {
        return [
            [['id' => 'name','msg' => 'Please enter a name.'],true],
            [['id' => 'fake'],false],
            [['msg' => 'A bad error'],false],
            ['An invalid error',false],
        ];
    }

    /**
     * Ensure modError.isNotFieldError properly checks for a non-field-based error
     * @param array $error
     * @param boolean $shouldPass
     * @dataProvider providerTestIsNotFieldError
     */
    public function testIsNotFieldError($error,$shouldPass = true) {
        $passed = $this->error->isNotFieldError($error);
        $this->assertEquals($shouldPass,$passed);
    }
    public function providerTestIsNotFieldError() {
        return [
            ['A standard error',true],
            [['id' => 'fake'],true],
            [['msg' => 'A bad error'],true],
            [['id' => 'name','msg' => 'Please enter a name.'],false],
        ];
    }

    /**
     * Tests that getErrors properly calculates the right number of errors, both when includeFields is true or false,
     * and returns them in the order they were given
     *
     * @depends testAddError
     * @depends testAddField
     */
    public function testGetErrors() {
        $this->error->addError('A test error');
        $this->error->addError('Another test error');
        $this->error->addField('name','A field-based error');
        $errors = $this->error->getErrors();
        $this->assertTrue(count($errors) == 2);

        $errors = $this->error->getErrors(true);
        $this->assertTrue(count($errors) == 3);

        $this->assertEquals('A test error',$errors[0]);
    }

    /**
     * Tests that getFields properly calculates the right number of field errors and properly returns the errors
     * in the order they were given
     *
     * @depends testAddError
     * @depends testAddField
     */
    public function testGetFields() {
        $this->error->addError('A test error that should be ignored');
        $this->error->addField('name','Please enter a valid name.');
        $this->error->addField('name','Please enter a valid name.');
        $this->error->addField('name','This name is fake.');
        $this->error->addField('description','Describe this, silly!');
        $errors = $this->error->getFields();
        $this->assertTrue(count($errors) == 4);

        $this->assertEquals('Please enter a valid name.',$errors[0]);
    }

    /**
     * Test the success method to ensure it sends back the right data
     *
     * @param string $message
     * @param int $id
     * @dataProvider providerTestSuccess
     */
    public function testSuccess($message,$id) {
        $response = $this->error->success($message, ['id' => $id]);

        $this->assertTrue($response['success']);
        $this->assertEquals($message,$response['message']);
        $this->assertEquals($id,$response['object']['id']);
    }
    public function providerTestSuccess() {
        return [
            ['A win occurred!',456],
        ];
    }

    /**
     * Test the failure method to ensure it sends back the right data
     * @return void
     */
    public function testFailure() {
        $generalErrorMessage = 'Please check the values in your form.';
        $this->error->addField('name','Name is required.');
        $response = $this->error->failure($generalErrorMessage, ['id' => 123]);

        $this->assertFalse($response['success']);
        $this->assertEquals($generalErrorMessage,$response['message']);
        $this->assertEquals(1,$response['total']);
        $this->assertEquals('name',$response['errors'][0]['id']);
        $this->assertEquals('Name is required.',$response['errors'][0]['msg']);
        $this->assertEquals(123,$response['object']['id']);
    }
}
