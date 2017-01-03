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

namespace modX\Tests\Controllers\Context;

use modX\Tests\MODxControllerTestCase;

class ContextUpdateControllerTest extends MODxControllerTestCase {

    protected $controllerName = 'ContextUpdateManagerController';
    protected $controllerPath = 'context/update';

    public function setUp() {
        parent::setUp();

        $this->controller->setProperty('key', 'web');
    }

    public function testControllerInitialize() {
        $this->controller->initialize();
        $this->assertNotEmpty($this->controller->context);
    }

    public function testControllerCustomCssJs() {
        $this->controller->loadCustomCssJs();
        $this->assertNotEmpty($this->controller->head['js']);
    }

    public function testControllerTemplateFile() {
        $templateFile = $this->controller->getTemplateFile();
        $this->assertEmpty($templateFile);
    }

    public function testControllerPageTitle() {
        $this->controller->initialize();
        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }

    public function testControllerProcess() {
        $this->controller->initialize();
        $this->controller->process();
        $this->assertNotEmpty($this->controller->getPlaceholder('_ctx'));
    }
}
