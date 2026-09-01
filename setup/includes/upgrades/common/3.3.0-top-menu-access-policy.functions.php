<?php

/**
 * Helpers for 3.3.0 top-menu access policy upgrade (#14498).
 *
 * @package setup
 */

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modMenu;
use MODX\Revolution\modX;

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

/**
 * @param array<string,mixed> $data
 * @return array<string,mixed>
 */
function modxUpgrade330TopMenuMigratePolicyData(array $data): array
{
    // Parent keys: preserve visibility for users who had the old shared child/page key.
    $parentGrants = [
        'menu_media' => 'file_manager',
        'menu_access' => 'access_permissions',
        'menu_system' => 'settings',
    ];
    foreach ($parentGrants as $parentKey => $legacyKey) {
        if (!empty($data[$legacyKey]) && !array_key_exists($parentKey, $data)) {
            $data[$parentKey] = true;
        }
    }

    foreach (
        [
            'view_eventlog',
            'actions',
            'logout',
            'about',
            'credits',
            'export_static',
            'menu_security',
            'menu_support',
            'menu_tools',
        ] as $key
    ) {
        unset($data[$key]);
    }

    return $data;
}

/**
 * @param list<array{name:string,description:string}> $definitions
 */
function modxUpgrade330TopMenuEnsureTemplatePermissions(modX $modx, array $definitions): void
{
    /** @var modAccessPolicyTemplate|null $adminTemplate */
    $adminTemplate = $modx->getObject(modAccessPolicyTemplate::class, [
        'name' => 'AdministratorTemplate',
    ]);
    if (!$adminTemplate instanceof modAccessPolicyTemplate) {
        return;
    }
    $templateId = (int)$adminTemplate->get('id');
    foreach ($definitions as $definition) {
        $existing = $modx->getObject(modAccessPermission::class, [
            'template' => $templateId,
            'name' => $definition['name'],
        ]);
        if ($existing instanceof modAccessPermission) {
            continue;
        }
        $permission = $modx->newObject(modAccessPermission::class);
        $permission->fromArray([
            'template' => $templateId,
            'name' => $definition['name'],
            'description' => $definition['description'],
            'value' => true,
        ], '', true, true);
        $permission->save();
    }
}

function modxUpgrade330TopMenuAccessPolicy(modX $modx): void
{
    $migrations = [
        ['text' => 'eventlog_viewer', 'from' => 'view_eventlog', 'to' => 'error_log_view'],
        ['text' => 'edit_menu', 'from' => 'actions', 'to' => 'menus'],
        ['text' => 'logout', 'from' => 'logout', 'to' => ''],
        ['text' => 'media', 'from' => 'file_manager', 'to' => 'menu_media'],
        ['text' => 'access', 'from' => 'access_permissions', 'to' => 'menu_access'],
        ['text' => 'admin', 'from' => 'settings', 'to' => 'menu_system'],
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

    modxUpgrade330TopMenuEnsureTemplatePermissions($modx, [
        ['name' => 'menu_media', 'description' => 'perm.menu_media_desc'],
        ['name' => 'menu_access', 'description' => 'perm.menu_access_desc'],
    ]);

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

    $retiredKeys = [
        'view_eventlog',
        'actions',
        'logout',
        'about',
        'credits',
        'export_static',
        'menu_security',
        'menu_support',
        'menu_tools',
    ];
    /** @var modAccessPermission[] $permissions */
    $permissions = $modx->getCollection(modAccessPermission::class, [
        'name:IN' => $retiredKeys,
    ]);
    foreach ($permissions as $permission) {
        $permission->remove();
    }
}
