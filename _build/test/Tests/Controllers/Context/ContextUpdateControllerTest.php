<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 */
namespace MODX\Revolution\Tests\Controllers\Context;


use MODX\Revolution\MODxControllerTestCase;

/**
 * Tests related to the Update Context controller
 *
 * @package modx-test
 * @subpackage modx
 * @group Controllers
 * @group Context
 * @group ContextControllers
 * @group ContextUpdateController
 */
class ContextUpdateControllerTest extends MODxControllerTestCase {
    /** @var \ContextUpdateManagerController $controller */
    public $controller;

    public $controllerName = 'ContextUpdateManagerController';
    public $controllerPath = 'context/update';

    public function setUp() {
        parent::setUp();
        $this->controller->setProperty('key','web');
    }

    /**
     * @return void
     */
    public function testInitialize() {
        $this->controller->initialize();
        $this->assertNotEmpty($this->controller->context);
    }
    /**
     * @return void
     */
    public function testLoadCustomCssJs() {
        $this->controller->loadCustomCssJs();
        $this->assertNotEmpty($this->controller->head['js']);
    }
    /**
     * @return void
     */
    public function testGetTemplateFile() {
        $templateFile = $this->controller->getTemplateFile();
        $this->assertEmpty($templateFile);
    }
    /**
     * @depends testInitialize
     */
    public function testGetPageTitle() {
        $this->controller->initialize();
        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }
    /**
     * @depends testInitialize
     */
    public function testProcess() {
        $this->controller->initialize();
        $this->controller->process();
        $this->assertNotEmpty($this->controller->getPlaceholder('_ctx'));
    }
}
