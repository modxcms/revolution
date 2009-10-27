<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modPropertySet']= array (
  'package' => 'modx',
  'table' => 'property_set',
  'fields' => 
  array (
    'name' => '',
    'category' => 0,
    'description' => '',
    'properties' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'category' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => true,
    ),
  ),
  'aggregates' => 
  array (
    'Category' => 
    array (
      'class' => 'modCategory',
      'key' => 'id',
      'local' => 'category',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'composites' => 
  array (
    'Elements' => 
    array (
      'class' => 'modElementPropertySet',
      'local' => 'id',
      'foreign' => 'property_set',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
