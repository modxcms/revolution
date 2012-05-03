<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modPrincipal']= array (
  'package' => 'modx',
  'version' => '1.1',
  'extends' => 'xPDOSimpleObject',
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
