<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modManagerUser']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'manager_users',
  'fields' => 
  array (
    'username' => '',
    'password' => '',
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
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
);
