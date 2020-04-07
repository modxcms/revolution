<?php
/**
 * The default Object Policy Template Permission scheme.
 *
 * @package modx
 */
use MODX\Revolution\modAccessPermission;

$permissions = [];
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
]);

return $permissions;
