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
use MODX\Revolution\modDashboardWidget;
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
 * @group modDashboardWidget
 */
class modDashboardWidgetTest extends MODxTestCase {
    /** @var modDashboardWidget $widget */
    public $widget;

    /**
     * Load some utility classes this case uses
     *
     * @return void
     * @throws xPDOException
     */
    public function setUp() {
        parent::setUp();
        require_once MODX_MANAGER_PATH.'controllers/default/welcome.class.php';

        $this->widget = $this->modx->newObject(modDashboardWidget::class);
        $this->widget->fromArray([
            'name' => 'w_recentlyeditedresources',
            'description' => 'w_recentlyeditedresources_desc',
            'type' => 'file',
            'size' => 'half',
            'content' => '[[++manager_path]]controllers/default/dashboard/widget.grid-rer.php',
            'namespace' => 'core',
            'lexicon' => 'core:dashboards',
        ]);
    }

    /**
     * Test the content rendering of a widget
     */
    public function testGetContent() {
        /** @var modManagerController $controller Fake running the welcome controller */
        $controller = new \WelcomeManagerController($this->modx, [
            'namespace' => 'core',
            'namespace_name' => 'core',
            'namespace_path' => MODX_MANAGER_PATH,
            'lang_topics' => 'dashboards',
            'controller' => 'system/dashboards',
        ]);
        /** @var modDashboard $dashboard */
        $output = $this->widget->getContent($controller);
        $this->assertNotEmpty($output);
    }
}
