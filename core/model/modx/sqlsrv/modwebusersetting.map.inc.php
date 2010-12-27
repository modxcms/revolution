<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modWebUserSetting']= array (
  'package' => 'modx',
  'version' => '1.1',
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
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'setting_name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
    'setting_value' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
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
