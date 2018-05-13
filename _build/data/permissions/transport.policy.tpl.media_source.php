<?php
/**
 * The default Permission scheme for the Media Source Policy.
 *
 * @package modx
 */
$permissions = array();
$permissions[] = $xpdo->newObject('MODX\modAccessPermission',array(
    'name' => 'create',
    'description' => 'perm.create_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('MODX\modAccessPermission',array(
    'name' => 'copy',
    'description' => 'perm.copy_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('MODX\modAccessPermission',array(
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('MODX\modAccessPermission',array(
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('MODX\modAccessPermission',array(
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('MODX\modAccessPermission',array(
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('MODX\modAccessPermission',array(
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
));

return $permissions;