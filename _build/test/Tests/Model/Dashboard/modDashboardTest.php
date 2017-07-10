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

namespace modX\Tests\Model\Dashboard;

use modX\Tests\MODxTestCase;

class modDashboardTest extends MODxTestCase {

    public function setUp() {
        parent::setUp();

        $this->modx->loadClass('modDashboard');
        $this->modx->loadClass('modManagerController', MODX_CORE_PATH . 'model/modx/', true, true);
        $this->modx->loadClass('modManagerControllerDeprecated',MODX_CORE_PATH . 'model/modx/', true, true);

        require_once MODX_MANAGER_PATH . 'controllers/default/welcome.class.php';
    }

    public function testGetDefaultDashboard() {
        $dashboard = \modDashboard::getDefaultDashboard($this->modx);
        $this->assertInstanceOf('modDashboard', $dashboard);
    }

    public function testDashboardRender() {
        $controller = new \WelcomeManagerController($this->modx, [
            'namespace' => 'core',
            'namespace_name' => 'core',
            'namespace_path' => MODX_MANAGER_PATH,
            'lang_topics' => 'dashboards',
            'controller' => 'system/dashboards',
        ]);

        $dashboard = \modDashboard::getDefaultDashboard($this->modx);
        $output = $dashboard->render($controller);
        $this->assertNotEmpty($output);
    }
}
