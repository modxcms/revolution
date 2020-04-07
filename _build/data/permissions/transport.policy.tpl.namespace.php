<?php
/**
 * The default Permission scheme for the Namespace Policy.
 *
 * @package modx
 */
use MODX\Revolution\modAccessPermission;

$permissions = [];
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
]);

return $permissions;
