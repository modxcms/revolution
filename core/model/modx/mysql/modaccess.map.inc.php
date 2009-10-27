<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccess']= array (
  'package' => 'modx',
  'fields' => 
  array (
    'target' => '',
    'principal_class' => 'modPrincipal',
    'principal' => 0,
    'authority' => 9999,
    'policy' => 0,
  ),
  'fieldMeta' => 
  array (
    'target' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fk',
    ),
    'principal_class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'modPrincipal',
      'index' => 'index',
    ),
    'principal' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
    'authority' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 9999,
      'index' => 'index',
    ),
    'policy' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
  ),
  'aggregates' => 
  array (
    'Policy' => 
    array (
      'class' => 'modAccessPolicy',
      'local' => 'policy',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Principal' => 
    array (
      'class' => 'modPrincipal',
      'local' => 'principal',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
