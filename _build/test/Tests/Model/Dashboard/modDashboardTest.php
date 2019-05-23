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
namespace MODX\Revolution\Tests\Model\Dashboard;


use MODX\Revolution\modDashboard;
use MODX\Revolution\modManagerController;
use MODX\Revolution\MODxTestCase;
use xPDO\xPDOException;

/**
 * Tests related to the modDashboard class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Dashboard
 * @group modDashboard
 */
class modDashboardTest extends MODxTestCase {
    /**
     * Load some utility classes this case uses
     *
     * @return void
     * @throws xPDOException
     */
    public function setUp() {
        parent::setUp();
        $this->modx->loadClass('modDashboard');
        $this->modx->loadClass('modManagerController',MODX_CORE_PATH.'model/modx/',true,true);
        $this->modx->loadClass('modManagerControllerDeprecated',MODX_CORE_PATH.'model/modx/',true,true);
        require_once MODX_MANAGER_PATH.'controllers/default/welcome.class.php';
    }

    /**
     * Ensure the static getDefaultDashboard method works, returning the default dashboard for the user
     */
    public function testGetDefaultDashboard() {
        /** @var modDashboard $dashboard */
        $dashboard = modDashboard::getDefaultDashboard($this->modx);
        $this->assertInstanceOf(modDashboard::class,$dashboard);
    }

    /**
     * Ensure the rendering of the dashboard works properly
     */
    public function testRender() {
        /** @var modManagerController $controller Fake running the welcome controller */
        $controller = new \WelcomeManagerController($this->modx,array(
            'namespace' => 'core',
            'namespace_name' => 'core',
            'namespace_path' => MODX_MANAGER_PATH,
            'lang_topics' => 'dashboards',
            'controller' => 'system/dashboards',
        ));
        /** @var modDashboard $dashboard */
        $dashboard = modDashboard::getDefaultDashboard($this->modx);
        $output = $dashboard->render($controller);
        $this->assertNotEmpty($output);
    }
}
