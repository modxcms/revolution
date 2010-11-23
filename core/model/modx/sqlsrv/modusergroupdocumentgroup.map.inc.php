<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modUserGroupDocumentGroup']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'membergroup_access',
  'fields' => 
  array (
    'membergroup' => 0,
    'documentgroup' => 0,
  ),
  'fieldMeta' => 
  array (
    'membergroup' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'documentgroup' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'UserGroup' => 
    array (
      'class' => 'modUserGroup',
      'key' => 'id',
      'local' => 'membergroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'DocumentGroup' => 
    array (
      'class' => 'modDocumentGroup',
      'key' => 'id',
      'local' => 'documentgroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
