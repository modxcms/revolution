<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modUserGroupRole']= array (
  'package' => 'modx',
  'table' => 'user_group_roles',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'authority' => 9999,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
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
  ),
  'aggregates' => 
  array (
    'UserGroupMembers' => 
    array (
      'class' => 'modUserGroupMember',
      'local' => 'id',
      'foreign' => 'role',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
