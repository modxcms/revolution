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
 * @group ContextListController
 */
class ContextListControllerTest extends MODxControllerTestCase {
    /** @var \ContextUpdateManagerController $controller */
    public $controller;

    public $controllerName = 'ContextManagerController';
    public $controllerPath = 'context/index';

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
     * @return void
     */
    public function testGetPageTitle() {
        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }
}
