<?php

/**
 * Common upgrade script for modify Access Permissions
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modAccessPermission;

$defaultAaccessPolicyTemplateIds = [1, 2, 3, 4, 5, 6, 7];
$permissionsRemove = ['about', 'credits', 'export_static', 'menu_security', 'menu_support'];

// Remove permissions
foreach ($permissionsRemove as $permissionItem) {
    /** @var modAccessPermission $permission */

    $c = $modx->newQuery(modAccessPermission::class);
    $c->where([
        'name' => $permissionItem,
        'template:IN' => $defaultAaccessPolicyTemplateIds,
    ]);
    $permissions = $modx->getIterator(modAccessPermission::class, $c);

    foreach($permissions as $permission) {
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