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
namespace MODX\Revolution\Tests\Controllers;


use MODX\Revolution\modDashboard;
use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\modDashboardWidgetPlacement;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\MODxControllerTestCase;

/**
 * Tests related to the Welcome controller
 *
 * @package modx-test
 * @subpackage modx
 * @group Controllers
 * @group Dashboard
 * @group WelcomeController
 */
class WelcomeControllerTest extends MODxControllerTestCase {
    /** @var \WelcomeManagerController $controller */
    public $controller;

    public $controllerName = 'WelcomeManagerController';
    public $controllerPath = 'welcome';

    public function setUp() {
        parent::setUp();

        /** @var modDashboard $dashboard */
        $this->controller->dashboard = $this->modx->newObject(modDashboard::class);
        $this->controller->dashboard->fromArray(array(
            'id' => 10000,
            'name' => 'Unit Test Dashboard',
        ),'',true,true);
        $this->controller->dashboard->save();

        /** @var modDashboardWidget $dashboardWidget */
        $dashboardWidget = $this->modx->newObject(modDashboardWidget::class);
        $dashboardWidget->fromArray(array(
            'id' => 10000,
            'name' => 'Unit Test Dashboard Widget',
            'type' => 'html',
            'content' => '<h2>Unit Test Widget Output</h2>',
            'namespace' => 'core',
            'lexicon' => 'core:dashboards',
            'size' => 'half',
        ),'',true,true);
        $dashboardWidget->save();

        /** @var modDashboardWidgetPlacement $dashboardWidgetPlacement */
        $dashboardWidgetPlacement = $this->modx->newObject(modDashboardWidgetPlacement::class);
        $dashboardWidgetPlacement->fromArray(array(
            'dashboard' => $this->controller->dashboard->get('id'),
            'widget' => $dashboardWidget->get('id'),
            'rank' => 0,
        ),'',true,true);
        $dashboardWidgetPlacement->save();

        /** @var modUserGroup $userGroup */
        $userGroup = $this->modx->newObject(modUserGroup::class);
        $userGroup->fromArray(array(
            'id' => 10000,
            'name' => 'Unit Test User Group 1',
            'parent' => 0,
            'rank' => 0,
            'dashboard' => 10000,
        ),'',true,true);
        $userGroup->save();

    }

    public function tearDown() {
        parent::tearDown();
        $userGroups = $this->modx->getCollection(modUserGroup::class, array('name:LIKE' => '%Unit Test%'));
        /** @var modUserGroup $userGroup */
        foreach ($userGroups as $userGroup) {
            $userGroup->remove();
        }

        $dashboards = $this->modx->getCollection(modDashboard::class, array('name:LIKE' => '%Unit Test%'));
        /** @var modDashboard $dashboard */
        foreach ($dashboards as $dashboard) {
            $dashboard->remove();
        }

        $widgets = $this->modx->getCollection(modDashboardWidget::class, array('name:LIKE' => '%Unit Test%'));
        /** @var modDashboardWidget $widget */
        foreach ($widgets as $widget) {
            $widget->remove();
        }

        $this->modx->user->set('primary_group',0);
    }

    /**
     * @param string|int $userGroupPk
     * @dataProvider providerGetDashboard
     */
    public function testGetDashboard($userGroupPk) {
        $this->modx->user->set('primary_group',$userGroupPk);
        $dashboard = $this->controller->dashboard;
        $this->assertInstanceOf(modDashboard::class, $dashboard);
    }
    /**
     * @return array
     */
    public function providerGetDashboard() {
        return array(
            array(0), /* default dashboard */
            array(10000),/* custom unit test dashboard */
            array(99999),/* invalid primary group, should fallback to default dashboard */
        );
    }

    /**
     * Run a test to ensure custom dashboards work as expected
     */
    public function testCustomDashboardRender() {
        $this->modx->user->set('primary_group',10000);
        $dashboard = $this->controller->dashboard;
        $content = $dashboard->render($this->controller);
        $this->assertContains('<h2>Unit Test Widget Output</h2>',$content);
    }

    /**
     * Test to see if the welcome screen loads as expected
     * @param boolean $showWelcomeScreen
     * @dataProvider providerWelcomeScreen
     */
    public function testWelcomeScreen($showWelcomeScreen) {
        $this->modx->setOption('welcome_screen',$showWelcomeScreen);
        $this->controller->checkForWelcomeScreen();
        $this->assertEquals($showWelcomeScreen,$this->controller->showWelcomeScreen);
    }
    /**
     * @return array
     */
    public function providerWelcomeScreen() {
        return array(
            array(false),
            array(true),
        );
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
        $this->assertNotEmpty($templateFile);
    }
    /**
     * @return void
     */
    public function testGetPageTitle() {
        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }
}
