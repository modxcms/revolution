<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modManagerUser']= array (
  'package' => 'modx',
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
  ),
);
