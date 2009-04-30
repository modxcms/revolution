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
    'CreatedBy' => 
    array (
      'class' => 'modResource',
      'key' => 'createdby',
      'local' => 'id',
      'foreign' => 'createdby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'EditedBy' => 
    array (
      'class' => 'modResource',
      'key' => 'editedby',
      'local' => 'id',
      'foreign' => 'editedby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'DeletedBy' => 
    array (
      'class' => 'modResource',
      'key' => 'deletedby',
      'local' => 'id',
      'foreign' => 'deletedby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'PublishedBy' => 
    array (
      'class' => 'modResource',
      'key' => 'publishedby',
      'local' => 'id',
      'foreign' => 'publishedby',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Sender' => 
    array (
      'class' => 'modUserMessage',
      'key' => 'sender',
      'local' => 'id',
      'foreign' => 'sender',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Recipient' => 
    array (
      'class' => 'modUserMessage',
      'key' => 'recipient',
      'local' => 'id',
      'foreign' => 'recipient',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'composites' => 
  array (
    'modUserProfile' => 
    array (
      'class' => 'modUserProfile',
      'key' => 'internalKey',
      'local' => 'id',
      'foreign' => 'internalKey',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'modUserSetting' => 
    array (
      'class' => 'modUserSetting',
      'key' => 'user',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modUserGroupMember' => 
    array (
      'class' => 'modUserGroupMember',
      'key' => 'member',
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
