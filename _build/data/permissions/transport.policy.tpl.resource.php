<?php
/**
 * The default Permission scheme for the Resource Policy Template.
 *
 * @package modx
 */
use MODX\Revolution\modAccessPermission;

$permissions = array();
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'add_children',
    'description' => 'perm.add_children_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'copy',
    'description' => 'perm.copy_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'create',
    'description' => 'perm.create_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'delete',
    'description' => 'perm.delete_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'move',
    'description' => 'perm.move_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'publish',
    'description' => 'perm.publish_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'steal_lock',
    'description' => 'perm.steal_lock_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'undelete',
    'description' => 'perm.undelete_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'unpublish',
    'description' => 'perm.unpublish_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
));

return $permissions;
