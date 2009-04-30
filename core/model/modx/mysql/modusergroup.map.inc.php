<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modUserGroup']= array (
  'package' => 'modx',
  'table' => 'membergroup_names',
  'fields' => 
  array (
    'name' => '',
    'parent' => 0,
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
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
    'Parent' => 
    array (
      'class' => 'modUserGroup',
      'local' => 'parent',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Children' => 
    array (
      'class' => 'modUserGroup',
      'local' => 'id',
      'foreign' => 'parent',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'composites' => 
  array (
    'modUserGroupMember' => 
    array (
      'class' => 'modUserGroupMember',
      'key' => 'user_group',
      'local' => 'id',
      'foreign' => 'user_group',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modUserGroup']['aggregates']= array_merge($xpdo_meta_map['modUserGroup']['aggregates'], array_change_key_case($xpdo_meta_map['modUserGroup']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modUserGroup']['composites']= array_merge($xpdo_meta_map['modUserGroup']['composites'], array_change_key_case($xpdo_meta_map['modUserGroup']['composites']));
$xpdo_meta_map['modusergroup']= & $xpdo_meta_map['modUserGroup'];
