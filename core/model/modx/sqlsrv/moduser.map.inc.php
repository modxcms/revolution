<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modUser']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'users',
  'extends' => 'modPrincipal',
  'fields' => 
  array (
    'username' => '',
    'password' => '',
    'cachepwd' => '',
    'class_key' => 'modUser',
    'active' => 1,
    'remote_key' => NULL,
    'remote_data' => NULL,
    'hash_class' => 'hashing.modNative',
    'salt' => '',
    'primary_group' => 0,
    'session_stale' => NULL,
    'sudo' => 0,
    'createdon' => 0,
  ),
  'fieldMeta' => 
  array (
    'username' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'password' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'cachepwd' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'class_key' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'modUser',
      'index' => 'index',
    ),
    'active' => 
    array (
      'dbtype' => 'bit',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 1,
    ),
    'remote_key' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'index' => 'index',
    ),
    'remote_data' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'json',
      'null' => true,
    ),
    'hash_class' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'hashing.modNative',
    ),
    'salt' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'primary_group' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'session_stale' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'array',
      'null' => true,
    ),
    'sudo' => 
    array (
      'dbtype' => 'bit',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'bigint',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'username' => 
    array (
      'alias' => 'username',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'username' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'class_key' => 
    array (
      'alias' => 'class_key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'class_key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'remote_key' => 
    array (
      'alias' => 'remote_key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'remote_key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'primary_group' => 
    array (
      'alias' => 'primary_group',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'primary_group' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
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
    'PrimaryGroup' => 
    array (
      'class' => 'modUserGroup',
      'local' => 'primary_group',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
