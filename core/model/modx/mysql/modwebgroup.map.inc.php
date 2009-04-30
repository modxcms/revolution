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
if (XPDO_PHP4_MODE) $xpdo_meta_map['modWebGroup']['composites']= array_merge($xpdo_meta_map['modWebGroup']['composites'], array_change_key_case($xpdo_meta_map['modWebGroup']['composites']));
$xpdo_meta_map['modwebgroup']= & $xpdo_meta_map['modWebGroup'];
