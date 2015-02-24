<?php
/**
 * The default Permission scheme for the Namespace Policy.
 *
 * @package modx
 */
$permissions = array();
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
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
));

return $permissions;