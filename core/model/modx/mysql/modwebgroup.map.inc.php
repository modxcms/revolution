<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modWebGroup']= array (
  'package' => 'modx',
  'table' => 'webgroup_names',
  'fields' => 
  array (
    'name' => '',
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
      'index' => 'unique',
    ),
  ),
  'composites' => 
  array (
    'modWebGroupDocumentGroup' => 
    array (
      'class' => 'modWebGroupDocumentGroup',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'webgroup',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modWebGroupMember' => 
    array (
      'class' => 'modWebGroupMember',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'webgroup',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
