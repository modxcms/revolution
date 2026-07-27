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
use MODX\Revolution\modDashboardWidgetPlacement;
use MODX\Revolution\modManagerController;
use MODX\Revolution\Processors\System\Dashboard\Update;
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
class modDashboardTest extends MODxTestCase
{
    /**
     * Load some utility classes this case uses
     *
     * @before
     * @return void
     * @throws xPDOException
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        require_once MODX_MANAGER_PATH . 'controllers/default/welcome.class.php';
    }

    /**
     * Ensure the static getDefaultDashboard method works, returning the default dashboard for the user
     */
    public function testGetDefaultDashboard()
    {
        /** @var modDashboard $dashboard */
        $dashboard = modDashboard::getDefaultDashboard($this->modx);
        $this->assertInstanceOf(modDashboard::class, $dashboard);
    }

    /**
     * Ensure the rendering of the dashboard works properly
     *
     * @medium
     */
    public function testRender()
    {
        /** @var modManagerController $controller Fake running the welcome controller */
        $controller = new \WelcomeManagerController($this->modx, [
            'namespace' => 'core',
            'namespace_name' => 'core',
            'namespace_path' => MODX_MANAGER_PATH,
            'lang_topics' => 'dashboards',
            'controller' => 'system/dashboards',
        ]);
        /** @var modDashboard $dashboard */
        $dashboard = modDashboard::getDefaultDashboard($this->modx);
        $output = $dashboard->render($controller);
        $this->assertNotEmpty($output);
    }

    /**
     * Regression test for #15390: when the template dashboard (user=0) is saved with fewer
     * widgets, user-specific placements must stay in sync and render() must not show removed widgets.
     * #14753 is the original report; core behavior was addressed in #15390 — this test guards that path.
     *
     * @medium
     */
    public function testRemovedWidgetsNotShownAfterUpdate()
    {
        $suffix = uniqid('widget_removal_', true);
        $dashboardName = 'Unit Test Dashboard Widget Removal ' . $suffix;
        $dashboardId = null;
        $widgetIds = [];

        try {
            /** @var modDashboard|null $dashboard */
            $dashboard = $this->modx->newObject(modDashboard::class);
            if ($dashboard === null) {
                $this->markTestSkipped('modDashboard model not available');
            }
            $dashboard->fromArray([
                'name' => $dashboardName,
                'description' => '',
                'customizable' => true,
            ]);
            $this->assertTrue($dashboard->save(), 'Failed to save test dashboard');
            $dashboardId = (int) $dashboard->get('id');

            foreach (['Widget One', 'Widget Two'] as $rank => $label) {
                /** @var modDashboardWidget|null $widget */
                $widget = $this->modx->newObject(modDashboardWidget::class);
                if ($widget === null) {
                    $this->markTestSkipped('modDashboardWidget model not available');
                }
                $widget->fromArray([
                    'name' => 'Unit Test ' . $label . ' ' . $suffix,
                    'type' => 'html',
                    'content' => '<div data-widget="' . $suffix . '-' . $rank . '">' . $label . '</div>',
                    'namespace' => 'core',
                    'lexicon' => 'core:dashboards',
                    'size' => 'half',
                ]);
                $this->assertTrue($widget->save(), 'Failed to save test widget');
                $widgetIds[] = (int) $widget->get('id');
            }

            $placement1 = $this->modx->newObject(modDashboardWidgetPlacement::class);
            if ($placement1 === null) {
                $this->markTestSkipped('modDashboardWidgetPlacement model not available');
            }
            $placement1->fromArray([
                'dashboard' => $dashboardId,
                'user' => 0,
                'widget' => $widgetIds[0],
                'rank' => 0,
            ]);
            $this->assertTrue($placement1->save(), 'Failed to save first template placement');

            $placement2 = $this->modx->newObject(modDashboardWidgetPlacement::class);
            if ($placement2 === null) {
                $this->markTestSkipped('modDashboardWidgetPlacement model not available');
            }
            $placement2->fromArray([
                'dashboard' => $dashboardId,
                'user' => 0,
                'widget' => $widgetIds[1],
                'rank' => 1,
            ]);
            $this->assertTrue($placement2->save(), 'Failed to save second template placement');

            $controller = new \WelcomeManagerController($this->modx, [
                'namespace' => 'core',
                'namespace_name' => 'core',
                'namespace_path' => MODX_MANAGER_PATH,
                'lang_topics' => 'dashboards',
                'controller' => 'system/dashboards',
            ]);

            $dashboard->render($controller);
            if ($this->modx->user === null) {
                $this->markTestSkipped('modx user not available');
            }
            $userId = $this->modx->user->get('id');
            $userPlacementsBefore = $this->modx->getCollection(modDashboardWidgetPlacement::class, [
                'dashboard' => $dashboardId,
                'user' => $userId,
            ]);
            $this->assertCount(
                2,
                $userPlacementsBefore,
                'User must have 2 placements after first render (addUserWidgets)'
            );

            $widgetsPayload = json_encode([
                ['widget' => $widgetIds[0], 'rank' => 0],
            ]);
            $result = $this->modx->runProcessor(Update::class, [
                'id' => $dashboardId,
                'name' => $dashboardName,
                'widgets' => $widgetsPayload,
            ]);
            $this->assertFalse($result->isError(), 'Dashboard update must succeed: ' . $result->getMessage());

            $userPlacementsAfter = $this->modx->getCollection(modDashboardWidgetPlacement::class, [
                'dashboard' => $dashboardId,
                'user' => $userId,
            ]);
            $this->assertCount(1, $userPlacementsAfter, 'Removed widget must be removed from user placements');

            $reloaded = $this->modx->getObject(modDashboard::class, $dashboardId);
            $this->assertInstanceOf(modDashboard::class, $reloaded);
            $output = $reloaded->render($controller);
            $this->assertStringContainsString('Widget One', $output);
            $this->assertStringNotContainsString('Widget Two', $output);
        } finally {
            $this->removeDashboardWidgetRemovalFixtures($dashboardId, $widgetIds);
        }
    }

    /**
     * @param int|null $dashboardId
     * @param int[] $widgetIds
     */
    private function removeDashboardWidgetRemovalFixtures(?int $dashboardId, array $widgetIds): void
    {
        if ($dashboardId !== null) {
            $this->modx->removeCollection(modDashboardWidgetPlacement::class, ['dashboard' => $dashboardId]);
            $dashboard = $this->modx->getObject(modDashboard::class, $dashboardId);
            if ($dashboard !== null) {
                $dashboard->remove();
            }
        }
        foreach ($widgetIds as $widgetId) {
            $widget = $this->modx->getObject(modDashboardWidget::class, $widgetId);
            if ($widget !== null) {
                $widget->remove();
            }
        }
    }
}
