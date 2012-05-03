<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modManagerLog']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'manager_log',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'user' => 0,
    'occurred' => '0000-00-00 00:00:00',
    'action' => '',
    'classKey' => '',
    'item' => '0',
  ),
  'fieldMeta' => 
  array (
    'user' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'occurred' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
      'default' => '0000-00-00 00:00:00',
    ),
    'action' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'classKey' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'item' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
  ),
  'aggregates' => 
  array (
    'User' => 
    array (
      'class' => 'modUser',
      'local' => 'user',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
