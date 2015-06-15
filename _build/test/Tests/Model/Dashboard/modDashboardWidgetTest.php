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
 * Tests related to the modDashboard class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Dashboard
 * @group modDashboardWidget
 */
class modDashboardWidgetTest extends MODxTestCase {
    /** @var modDashboardWidget $widget */
    public $widget;
    /**
     * Load some utility classes this case uses
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->modx->loadClass('modDashboard');
        $this->modx->loadClass('modDashboardWidget');
        $this->modx->loadClass('modManagerController',MODX_CORE_PATH.'model/modx/',true,true);
        $this->modx->loadClass('modManagerControllerDeprecated',MODX_CORE_PATH.'model/modx/',true,true);
        require_once MODX_MANAGER_PATH.'controllers/default/welcome.class.php';

        $this->widget = $this->modx->newObject('modDashboardWidget');
        $this->widget->fromArray(array(
            'name' => 'w_recentlyeditedresources',
            'description' => 'w_recentlyeditedresources_desc',
            'type' => 'file',
            'size' => 'half',
            'content' => '[[++manager_path]]controllers/default/dashboard/widget.grid-rer.php',
            'namespace' => 'core',
            'lexicon' => 'core:dashboards',
        ));
    }

    /**
     * Test the content rendering of a widget
     */
    public function testGetContent() {
        /** @var modManagerController $controller Fake running the welcome controller */
        $controller = new WelcomeManagerController($this->modx,array(
            'namespace' => 'core',
            'namespace_name' => 'core',
            'namespace_path' => MODX_MANAGER_PATH,
            'lang_topics' => 'dashboards',
            'controller' => 'system/dashboards',
        ));
        /** @var modDashboard $dashboard */
        $output = $this->widget->getContent($controller);
        $this->assertNotEmpty($output);
    }
}
