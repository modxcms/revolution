<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessCategory']= array (
  'package' => 'modx',
  'table' => 'access_category',
  'fields' => 
  array (
    'context_key' => '',
  ),
  'fieldMeta' => 
  array (
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fk',
    ),
  ),
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modCategory',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Context' => 
    array (
      'class' => 'modContext',
      'local' => 'context_key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
