<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modWebUserSetting']= array (
  'package' => 'modx',
  'table' => 'web_user_settings',
  'fields' => 
  array (
    'webuser' => 0,
    'setting_name' => '',
    'setting_value' => '',
  ),
  'fieldMeta' => 
  array (
    'webuser' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'setting_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
    'setting_value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'modWebUser' => 
    array (
      'class' => 'modWebUser',
      'key' => 'id',
      'local' => 'webuser',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
