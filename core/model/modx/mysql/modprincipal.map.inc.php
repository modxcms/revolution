<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modPrincipal']= array (
  'package' => 'modx',
  'version' => '1.1',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
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
