<?php

/**
 * Align top-menu access policy keys for Error Log, Menus, and Logout (#14498).
 *
 * Menu rows and the Menus controller use the same keys as their processors.
 * Retired aliases are removed from policy data without granting canonical keys
 * (avoids elevating menu-only grants to page/processor access).
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modMenu;
use MODX\Revolution\modX;

if (!function_exists('modxUpgrade330TopMenuReplacePermissionToken')) {
    /**
     * Replace one permission token in a comma-separated permissions string.
     */
    function modxUpgrade330TopMenuReplacePermissionToken(string $permissions, string $from, string $to): string
    {
        if ($from === '') {
            return $permissions;
        }
        $tokens = array_values(array_filter(array_map('trim', explode(',', $permissions)), static function ($token) {
            return $token !== '';
        }));
        $replaced = false;
        foreach ($tokens as $index => $token) {
            if ($token !== $from) {
                continue;
            }
            if ($to === '') {
                unset($tokens[$index]);
            } else {
                $tokens[$index] = $to;
            }
            $replaced = true;
        }
        if (!$replaced) {
            return $permissions;
        }

        return implode(',', array_values($tokens));
    }
}

if (!function_exists('modxUpgrade330TopMenuMigratePolicyData')) {
    /**
     * Drop retired permission keys from policy data without elevating canonical grants.
     */
    function modxUpgrade330TopMenuMigratePolicyData(array $data): array
    {
        foreach (['view_eventlog', 'actions', 'logout'] as $key) {
            unset($data[$key]);
        }

        return $data;
    }
}

if (!function_exists('modxUpgrade330TopMenuAccessPolicy')) {
    function modxUpgrade330TopMenuAccessPolicy(modX $modx): void
    {
        $migrations = [
            ['text' => 'eventlog_viewer', 'from' => 'view_eventlog', 'to' => 'error_log_view'],
            ['text' => 'edit_menu', 'from' => 'actions', 'to' => 'menus'],
            ['text' => 'logout', 'from' => 'logout', 'to' => ''],
        ];

        foreach ($migrations as $migration) {
            /** @var modMenu|null $menu */
            $menu = $modx->getObject(modMenu::class, ['text' => $migration['text']]);
            if (!$menu instanceof modMenu) {
                continue;
            }
            $current = (string)$menu->get('permissions');
            $updated = modxUpgrade330TopMenuReplacePermissionToken(
                $current,
                $migration['from'],
                $migration['to']
            );
            if ($updated === $current) {
                continue;
            }
            $menu->set('permissions', $updated);
            $menu->save();
        }

        /** @var modAccessPolicy[] $policies */
        $policies = $modx->getCollection(modAccessPolicy::class);
        foreach ($policies as $policy) {
            $data = $policy->get('data');
            if (!is_array($data)) {
                continue;
            }
            $migrated = modxUpgrade330TopMenuMigratePolicyData($data);
            if ($migrated === $data) {
                continue;
            }
            $policy->set('data', $migrated);
            $policy->save();
        }

        $retiredKeys = ['view_eventlog', 'actions', 'logout'];
        /** @var modAccessPermission[] $permissions */
        $permissions = $modx->getCollection(modAccessPermission::class, [
            'name:IN' => $retiredKeys,
        ]);
        foreach ($permissions as $permission) {
            $permission->remove();
        }
    }
}

if (!defined('MODX_UPGRADE_330_TOP_MENU_NO_RUN')) {
    modxUpgrade330TopMenuAccessPolicy($modx);
}
