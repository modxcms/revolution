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
