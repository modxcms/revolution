<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessPermission']= array (
  'package' => 'modx',
  'table' => 'access_permissions',
  'fields' => 
  array (
    'policy' => 0,
    'name' => '',
    'description' => '',
    'value' => 1,
  ),
  'fieldMeta' => 
  array (
    'policy' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'value' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 1,
    ),
  ),
  'aggregates' => 
  array (
    'Policy' => 
    array (
      'class' => 'modAccessPolicy',
      'local' => 'policy',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
