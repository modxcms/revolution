<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modUser']= array (
  'package' => 'modx',
  'table' => 'users',
  'fields' => 
  array (
    'username' => '',
    'password' => '',
    'cachepwd' => '',
    'class_key' => 'modUser',
  ),
  'fieldMeta' => 
  array (
    'username' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'password' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'cachepwd' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'class_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'modUser',
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
    'CreatedResources' => 
    array (
      'class' => 'modResource',
      'local' => 'id',
      'foreign' => 'createdby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'EditedResources' => 
    array (
      'class' => 'modResource',
      'local' => 'id',
      'foreign' => 'editedby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'DeletedResources' => 
    array (
      'class' => 'modResource',
      'local' => 'id',
      'foreign' => 'deletedby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'PublishedResources' => 
    array (
      'class' => 'modResource',
      'local' => 'id',
      'foreign' => 'publishedby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'SentMessages' => 
    array (
      'class' => 'modUserMessage',
      'local' => 'id',
      'foreign' => 'sender',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'ReceivedMessages' => 
    array (
      'class' => 'modUserMessage',
      'local' => 'id',
      'foreign' => 'recipient',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'composites' => 
  array (
    'Profile' => 
    array (
      'class' => 'modUserProfile',
      'local' => 'id',
      'foreign' => 'internalKey',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'UserSettings' => 
    array (
      'class' => 'modUserSetting',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'UserGroupMembers' => 
    array (
      'class' => 'modUserGroupMember',
      'local' => 'id',
      'foreign' => 'member',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modUser']['aggregates']= array_merge($xpdo_meta_map['modUser']['aggregates'], array_change_key_case($xpdo_meta_map['modUser']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modUser']['composites']= array_merge($xpdo_meta_map['modUser']['composites'], array_change_key_case($xpdo_meta_map['modUser']['composites']));
$xpdo_meta_map['moduser']= & $xpdo_meta_map['modUser'];
