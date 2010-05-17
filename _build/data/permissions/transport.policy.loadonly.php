<?php
/**
 * A load-only Permission scheme.
 *
 * @package modx
 */
$permissions = array();
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
));

return $permissions;