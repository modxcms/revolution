<?php

namespace MODX\Revolution\Tests\Controllers;

use MODX\Revolution\MODxTestCase;

/**
 * Tests for Top menu access policy alignment (#14498)
 *
 * @package modx-test
 * @group Controllers
 */
class TopMenuAccessPolicyTest extends MODxTestCase
{
    private const RETIRED_KEYS = ['actions', 'logout', 'view_eventlog'];

    private const MENUS_TRANSPORT = '_build/data/transport.core.menus.php';

    private const ADMIN_TEMPLATE = '_build/data/permissions/transport.policy.tpl.administrator.php';

    private const CORE_POLICIES = '_build/data/transport.core.accesspolicies.php';

    private const UPGRADE_SCRIPT = 'setup/includes/upgrades/common/3.3.0-top-menu-access-policy.php';

    private function buildData(string $relativePath): string
    {
        return file_get_contents(MODX_BASE_PATH . $relativePath);
    }

    private function loadUpgradeHelpers(): void
    {
        if (!defined('MODX_UPGRADE_330_TOP_MENU_NO_RUN')) {
            define('MODX_UPGRADE_330_TOP_MENU_NO_RUN', true);
        }
        require_once MODX_BASE_PATH . self::UPGRADE_SCRIPT;
    }

    private function assertMenuPermission(string $menuText, string $permission): void
    {
        $pattern = sprintf(
            "/'text'\\s*=>\\s*'%s'[\\s\\S]*?'permissions'\\s*=>\\s*'%s'/",
            $menuText,
            $permission
        );
        $this->assertMatchesRegularExpression($pattern, $this->buildData(self::MENUS_TRANSPORT));
    }

    private function assertMenuNotPermission(string $menuText, string $permission): void
    {
        $pattern = sprintf(
            "/'text'\\s*=>\\s*'%s'[\\s\\S]*?'permissions'\\s*=>\\s*'%s'/",
            $menuText,
            $permission
        );
        $this->assertDoesNotMatchRegularExpression($pattern, $this->buildData(self::MENUS_TRANSPORT));
    }

    public function testErrorLogMenuUsesErrorLogView()
    {
        $this->assertMenuPermission('eventlog_viewer', 'error_log_view');
        $this->assertMenuNotPermission('eventlog_viewer', 'view_eventlog');
    }

    public function testErrorLogControllerUsesErrorLogView()
    {
        $controller = file_get_contents(MODX_MANAGER_PATH . 'controllers/default/system/event.class.php');
        $this->assertStringContainsString("hasPermission('error_log_view')", $controller);
        $this->assertStringNotContainsString("hasPermission('view_eventlog')", $controller);
    }

    public function testMenusMenuAndControllerUseMenusPermission()
    {
        $this->assertMenuPermission('edit_menu', 'menus');
        $this->assertMenuNotPermission('edit_menu', 'actions');

        $controller = file_get_contents(MODX_MANAGER_PATH . 'controllers/default/system/action.class.php');
        $this->assertStringContainsString("hasPermission('menus')", $controller);
        $this->assertStringNotContainsString("hasPermission('actions')", $controller);
    }

    public function testMenuProcessorsUseMenusPermission()
    {
        $processorDir = MODX_CORE_PATH . 'src/Revolution/Processors/System/Menu/';
        foreach (['Create.php', 'GetList.php', 'GetNodes.php', 'Remove.php', 'Sort.php', 'Update.php'] as $file) {
            $path = $processorDir . $file;
            $this->assertFileExists($path);
            $contents = file_get_contents($path);
            $this->assertTrue(
                str_contains($contents, "hasPermission('menus')") || str_contains($contents, "\$permission = 'menus'"),
                $file . ' must gate on menus'
            );
        }
    }

    public function testLogoutMenuHasNoPermissionGate()
    {
        $this->assertMenuPermission('logout', '');
    }

    public function testRetiredKeysRemovedFromAdministratorTemplate()
    {
        $template = $this->buildData(self::ADMIN_TEMPLATE);
        foreach (self::RETIRED_KEYS as $key) {
            $this->assertDoesNotMatchRegularExpression(
                "/'name'\\s*=>\\s*'" . preg_quote($key, '/') . "'/",
                $template,
                $key . ' must be removed from AdministratorTemplate'
            );
        }
        $this->assertMatchesRegularExpression("/'name'\\s*=>\\s*'menus'/", $template);
        $this->assertMatchesRegularExpression("/'name'\\s*=>\\s*'error_log_view'/", $template);
    }

    public function testRetiredKeysRemovedFromCorePolicyData()
    {
        $policies = $this->buildData(self::CORE_POLICIES);
        foreach (self::RETIRED_KEYS as $key) {
            $this->assertStringNotContainsString(
                "'" . $key . "'",
                $policies,
                $key . ' must be removed from core policy data'
            );
        }
        $this->assertStringContainsString("'menus'", $policies);
        $this->assertStringContainsString("'error_log_view'", $policies);
    }

    public function testMgrLogViewLexiconDocumentsReportsMenu()
    {
        $lexicon = file_get_contents(MODX_CORE_PATH . 'lexicon/en/permissions.inc.php');
        $this->assertStringContainsString(
            "\$_lang['perm.mgr_log_view_desc'] = 'To view Manager actions under Reports (system/logs).';",
            $lexicon
        );
    }

    public function testUpgradeHelpersReplaceCompositeMenuPermissions()
    {
        $this->loadUpgradeHelpers();
        $this->assertSame(
            'error_log_view,custom_extra',
            modxUpgrade330TopMenuReplacePermissionToken('view_eventlog,custom_extra', 'view_eventlog', 'error_log_view')
        );
        $this->assertSame(
            'menus',
            modxUpgrade330TopMenuReplacePermissionToken('actions', 'actions', 'menus')
        );
        $this->assertSame(
            '',
            modxUpgrade330TopMenuReplacePermissionToken('logout', 'logout', '')
        );
        $this->assertSame(
            'keep_me',
            modxUpgrade330TopMenuReplacePermissionToken('keep_me', 'logout', '')
        );
    }

    public function testUpgradeHelpersDoNotElevateCanonicalPermissions()
    {
        $this->loadUpgradeHelpers();
        $migrated = modxUpgrade330TopMenuMigratePolicyData([
            'view_eventlog' => true,
            'actions' => true,
            'logout' => true,
            'menus' => false,
            'error_log_view' => false,
            'frames' => true,
        ]);
        $this->assertArrayNotHasKey('view_eventlog', $migrated);
        $this->assertArrayNotHasKey('actions', $migrated);
        $this->assertArrayNotHasKey('logout', $migrated);
        $this->assertFalse($migrated['menus']);
        $this->assertFalse($migrated['error_log_view']);
        $this->assertTrue($migrated['frames']);
    }

    public function testUpgradeScriptWiredInMysqlRunner()
    {
        $this->assertFileExists(MODX_BASE_PATH . self::UPGRADE_SCRIPT);
        $this->assertFileExists(MODX_BASE_PATH . 'setup/includes/upgrades/mysql/3.3.0-pl.php');
        $pl = file_get_contents(MODX_BASE_PATH . 'setup/includes/upgrades/mysql/3.3.0-pl.php');
        $this->assertStringContainsString('3.3.0-top-menu-access-policy.php', $pl);
    }
}
