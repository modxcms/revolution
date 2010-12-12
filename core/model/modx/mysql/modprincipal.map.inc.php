<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modPrincipal']= array (
  'package' => 'modx',
  'version' => '1.1',
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
