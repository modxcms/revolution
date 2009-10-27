<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modWebGroupMember']= array (
  'package' => 'modx',
  'table' => 'web_groups',
  'fields' => 
  array (
    'webgroup' => 0,
    'webuser' => 0,
  ),
  'fieldMeta' => 
  array (
    'webgroup' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'webuser' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'modWebGroup' => 
    array (
      'class' => 'modWebGroup',
      'key' => 'id',
      'local' => 'webgroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
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
