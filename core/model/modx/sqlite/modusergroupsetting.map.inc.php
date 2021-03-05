<?php
/**
 * @package modx
 * @subpackage sqlite
 */
$xpdo_meta_map['modUserGroupSetting']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'user_group_settings',
  'extends' => 'xPDOObject',
  'fields' =>
  array (
    'group' => 0,
    'key' => NULL,
    'value' => NULL,
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => '',
    'editedon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'group' => 
    array (
      'dbtype' => 'integer',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'key' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'value' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '1000',
      'phptype' => 'string',
      'default' => '',
    ),
    'xtype' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '75',
      'phptype' => 'string',
      'null' => false,
      'default' => 'textfield',
    ),
    'namespace' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core',
    ),
    'area' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'editedon' => 
    array (
        'dbtype' => 'bigint',
        'phptype' => 'timestamp',
        'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'group' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'UserGroup' => 
    array (
      'class' => 'modUserGroup',
      'local' => 'group',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Namespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
