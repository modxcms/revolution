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

namespace modX\Tests\Controllers;

use modX\Tests\MODxControllerTestCase;

class WelcomeControllerTest extends MODxControllerTestCase {

    protected $controllerName = 'WelcomeManagerController';
    protected $controllerPath = 'welcome';

    public function setUp() {
        parent::setUp();

        $this->controller->dashboard = $this->modx->newObject('modDashboard');
        $this->controller->dashboard->fromArray([
            'id' => 10000,
            'name' => 'Unit Test Dashboard',
        ], '', true, true);
        $this->controller->dashboard->save();


        $dashboardWidget = $this->modx->newObject('modDashboardWidget');
        $dashboardWidget->fromArray([
            'id' => 10000,
            'name' => 'Unit Test Dashboard Widget',
            'type' => 'html',
            'content' => '<h2>Unit Test Widget Output</h2>',
            'namespace' => 'core',
            'lexicon' => 'core:dashboards',
            'size' => 'half',
        ], '', true, true);
        $dashboardWidget->save();

        $dashboardWidgetPlacement = $this->modx->newObject('modDashboardWidgetPlacement');
        $dashboardWidgetPlacement->fromArray([
            'dashboard' => $this->controller->dashboard->get('id'),
            'widget' => $dashboardWidget->get('id'),
            'rank' => 0,
        ], '', true, true);
        $dashboardWidgetPlacement->save();

        $userGroup = $this->modx->newObject('modUserGroup');
        $userGroup->fromArray([
            'id' => 10000,
            'name' => 'Unit Test User Group 1',
            'parent' => 0,
            'rank' => 0,
            'dashboard' => 10000,
        ], '', true, true);
        $userGroup->save();
    }

    public function tearDown() {
        parent::tearDown();

        $this->modx->removeCollection('modDashboard', 10000);
        $this->modx->removeCollection('modUserGroup', 10000);
        $this->modx->removeCollection('modDashboardWidget', 10000);

        $this->modx->user->set('primary_group',0);
    }

    public function getUserDefaultDashboard() {
        $this->modx->user->set('primary_group', 0);
        $dashboard = $this->controller->dashboard;
        $this->assertInstanceOf('modDashboard', $dashboard);
    }

    public function getUserCustomDashboard() {
        $this->modx->user->set('primary_group', 10000);
        $dashboard = $this->controller->dashboard;
        $this->assertInstanceOf('modDashboard', $dashboard);
    }

    public function getUserFallbackNonExistingDashboard() {
        // No dashboard exists. MODX should fall back to the default dashboard.
        $this->modx->user->set('primary_group', 99999);
        $dashboard = $this->controller->dashboard;
        $this->assertInstanceOf('modDashboard', $dashboard);
    }

    public function testCustomDashboardRender() {
        $this->modx->user->set('primary_group', 10000);
        $dashboard = $this->controller->dashboard;
        $content = $dashboard->render($this->controller);
        $this->assertContains('<h2>Unit Test Widget Output</h2>', $content);
    }

    public function testWelcomeScreenDisabled() {
        $this->modx->setOption('welcome_screen', false);

        // Note: This method refreshes the value, it does not return the boolean value
        $this->controller->checkForWelcomeScreen();

        $this->assertFalse($this->controller->showWelcomeScreen);
    }

    public function testWelcomeScreenEnabled() {
        $this->modx->setOption('welcome_screen', true);

        // Note: This method refreshes the value, it does not return the boolean value
        $this->controller->checkForWelcomeScreen();

        $this->assertTrue($this->controller->showWelcomeScreen);
    }

    public function testControllerCustomCSSJS() {
        $this->controller->loadCustomCssJs();
        $this->assertNotEmpty($this->controller->head['js']);
    }

    public function testControllerTemplateFile() {
        $templateFile = $this->controller->getTemplateFile();
        $this->assertNotEmpty($templateFile);
    }

    public function testControllerPageTitle() {
        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }
}
