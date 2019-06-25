<?php
/**
 * The default Context Policy Template Permission scheme.
 *
 * @var modX|xPDO $xpdo
 * @package modx
 */
use MODX\Revolution\modAccessPermission;

$permissions = array();
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'view_unpublished',
    'description' => 'perm.view_unpublished_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject(modAccessPermission::class,array(
    'name' => 'copy',
    'description' => 'perm.copy_desc',
    'value' => true,
));

return $permissions;
