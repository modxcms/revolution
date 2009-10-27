<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modWorkspace']= array (
  'package' => 'modx',
  'table' => 'workspaces',
  'fields' => 
  array (
    'name' => '',
    'path' => '',
    'created' => NULL,
    'active' => 0,
    'attributes' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'path' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'created' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'timestamp',
      'null' => false,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'attributes' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'array',
    ),
  ),
  'composites' => 
  array (
    'Packages' => 
    array (
      'class' => 'transport.modTransportPackage',
      'local' => 'id',
      'foreign' => 'workspace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
