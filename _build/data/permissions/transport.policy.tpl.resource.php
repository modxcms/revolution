<?php
/**
 * The default Permission scheme for the Resource Policy Template.
 *
 * @package modx
 */
use MODX\Revolution\modAccessPermission;

$permissions = [];
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'add_children',
    'description' => 'perm.add_children_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'copy',
    'description' => 'perm.copy_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'create',
    'description' => 'perm.create_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete',
    'description' => 'perm.delete_desc',
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
    'name' => 'move',
    'description' => 'perm.move_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'publish',
    'description' => 'perm.publish_desc',
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
    'name' => 'steal_lock',
    'description' => 'perm.steal_lock_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'undelete',
    'description' => 'perm.undelete_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'unpublish',
    'description' => 'perm.unpublish_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
]);

return $permissions;
