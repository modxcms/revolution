<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modWebGroupDocumentGroup']= array (
  'package' => 'modx',
  'table' => 'webgroup_access',
  'fields' => 
  array (
    'webgroup' => 0,
    'documentgroup' => 0,
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
    'documentgroup' => 
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
    'modDocumentGroup' => 
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
if (XPDO_PHP4_MODE) $xpdo_meta_map['modWebGroupDocumentGroup']['aggregates']= array_merge($xpdo_meta_map['modWebGroupDocumentGroup']['aggregates'], array_change_key_case($xpdo_meta_map['modWebGroupDocumentGroup']['aggregates']));
$xpdo_meta_map['modwebgroupdocumentgroup']= & $xpdo_meta_map['modWebGroupDocumentGroup'];
