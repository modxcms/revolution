<?php
/**
 * The default Permission scheme for the Resource Policy.
 *
 * @package modx
 */
$permissions = array();
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'add_children',
    'description' => 'perm.add_children_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'create',
    'description' => 'perm.create_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete',
    'description' => 'perm.delete_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'move',
    'description' => 'perm.move_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'publish',
    'description' => 'perm.publish_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'steal_lock',
    'description' => 'perm.steal_lock_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'undelete',
    'description' => 'perm.undelete_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'unpublish',
    'description' => 'perm.unpublish_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
));

return $permissions;