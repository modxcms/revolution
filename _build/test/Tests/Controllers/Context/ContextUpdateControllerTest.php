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
    /** @var ContextUpdateManagerController $controller */
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
