<?php
/**
 * The default Permission scheme for the Media Source Policy.
 *
 * @package modx
 */
use MODX\Revolution\modAccessPermission;

$permissions = [];
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'create',
    'description' => 'perm.create_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'copy',
    'description' => 'perm.copy_desc',
    'value' => true,
]);
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
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
]);

return $permissions;
