<?php

/**
 * Common upgrade script for modify Access Permissions
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modMenu;

$defaultAaccessPolicyTemplateIds = [1, 2, 3, 4, 5, 6, 7];
$permissionsRemove = ['about', 'credits', 'export_static', 'menu_security', 'menu_support', 'menu_tools'];
$permissionsUpdate = [
    'menu_site' => [
        'name' => 'menu_content',
        'description' => 'perm.menu_content_desc',
        'menu_text' => 'site',
        'menu_only' => false,
    ],
    'menu_trash' => [
        'name' => 'trash_view',
        'description' => 'perm.trash_view_desc',
        'menu_text' => '',
        'menu_only' => false,
    ],
    'components' => [
        'name' => 'menu_packages',
        'description' => 'perm.menu_packages_desc',
        'menu_text' => 'components',
        'menu_only' => false,
    ],
    'settings' => [
        'name' => 'menu_system',
        'description' => 'perm.menu_system_desc',
        'menu_text' => 'admin',
        'menu_only' => true,
    ],
    'file_manager' => [
        'name' => 'menu_media',
        'description' => 'perm.menu_media_desc',
        'menu_text' => 'media',
        'menu_only' => true,
    ],
];

// Remove permissions
foreach ($permissionsRemove as $permissionItem) {
    /** @var modAccessPermission $permission */

    $c = $modx->newQuery(modAccessPermission::class);
    $c->where([
        'name' => $permissionItem,
        'template:IN' => $defaultAaccessPolicyTemplateIds,
    ]);
    $permissions = $modx->getIterator(modAccessPermission::class, $c);

    foreach ($permissions as $permission) {
        if ($permission instanceof modAccessPermission) {
            if ($permission->remove()) {
                $this->runner->addResult(
                    modInstallRunner::RESULT_SUCCESS,
                    sprintf($messageTemplate, 'ok', $this->install->lexicon('permission_remove_success', ['name' => $permissionItem]))
                );
            } else {
                $this->runner->addResult(
                    modInstallRunner::RESULT_WARNING,
                    sprintf($messageTemplate, 'warning', $this->install->lexicon('permission_remove_failed', ['name' => $permissionItem]))
                );
            }
        }
    }
}

// Update permissions
foreach ($permissionsUpdate as $permissionName => $permissionItem) {
    /** @var modAccessPermission $permission */
    $c = $modx->newQuery(modAccessPermission::class);
    $c->where([
        'name' => $permissionName,
        'template:IN' => $defaultAaccessPolicyTemplateIds,
    ]);
    $permissions = $modx->getIterator(modAccessPermission::class, $c);

    foreach ($permissions as $permission) {
        if ($permission instanceof modAccessPermission) {
            // Update only menu or menu and permission
            if ($permissionItem['menu_only']) {
                $permission->set('name', $permissionName);
            } else {
                $permission->set('name', $permissionItem['name']);
                $permission->set('description', $permissionItem['description']);
            }

            if ($permission->save()) {
                $this->runner->addResult(
                    modInstallRunner::RESULT_SUCCESS,
                    sprintf($messageTemplate, 'ok', $this->install->lexicon('permission_update_success', ['name' => $permissionName]))
                );

                // Update menu item
                $menuItem = $modx->getObject(modMenu::class, ['text' => $permissionItem['menu_text'], 'permissions' => $permissionName]);
                if ($menuItem) {
                    $menuItem->set('permissions', $permissionItem['name']);
                    $menuItem->save();
                }
            } else {
                $this->runner->addResult(
                    modInstallRunner::RESULT_WARNING,
                    sprintf($messageTemplate, 'warning', $this->install->lexicon('permission_update_failed', ['name' => $permissionName]))
                );
            }
        }
    }
}