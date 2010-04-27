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
    'active' => 1,
    'remote_key' => NULL,
    'remote_data' => NULL,
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
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 1,
    ),
    'remote_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'index' => 'index',
    ),
    'remote_data' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
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
