<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modSession']= array (
  'package' => 'modx',
  'table' => 'session',
  'fields' => 
  array (
    'id' => '',
    'access' => NULL,
    'data' => NULL,
  ),
  'fieldMeta' => 
  array (
    'id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
      'default' => '',
    ),
    'access' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => true,
      'attributes' => 'unsigned',
    ),
    'data' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
    ),
  ),
);
