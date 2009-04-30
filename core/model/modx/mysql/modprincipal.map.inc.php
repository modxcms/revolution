<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modPrincipal']= array (
  'package' => 'modx',
  'composites' => 
  array (
    'Acls' => 
    array (
      'class' => 'modAccess',
      'local' => 'id',
      'foreign' => 'principal',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modPrincipal']['composites']= array_merge($xpdo_meta_map['modPrincipal']['composites'], array_change_key_case($xpdo_meta_map['modPrincipal']['composites']));
$xpdo_meta_map['modprincipal']= & $xpdo_meta_map['modPrincipal'];
