<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modUserGroupMember']= array (
  'package' => 'modx',
  'table' => 'member_groups',
  'fields' => 
  array (
    'user_group' => 0,
    'member' => 0,
    'role' => 1,
  ),
  'fieldMeta' => 
  array (
    'user_group' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'member' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'role' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
    'modUserGroupRole' => 
    array (
      'class' => 'modUserGroupRole',
      'local' => 'role',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modUserGroup' => 
    array (
      'class' => 'modUserGroup',
      'local' => 'user_group',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modUser' => 
    array (
      'class' => 'modUser',
      'local' => 'member',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modUserGroupMember']['aggregates']= array_merge($xpdo_meta_map['modUserGroupMember']['aggregates'], array_change_key_case($xpdo_meta_map['modUserGroupMember']['aggregates']));
$xpdo_meta_map['modusergroupmember']= & $xpdo_meta_map['modUserGroupMember'];
