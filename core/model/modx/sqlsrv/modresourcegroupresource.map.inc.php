<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modResourceGroupResource']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'document_groups',
  'fields' => 
  array (
    'document_group' => 0,
    'document' => 0,
  ),
  'fieldMeta' => 
  array (
    'document_group' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'document' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'document_group' => 
    array (
      'alias' => 'document_group',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'document_group' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'document' => 
    array (
      'alias' => 'document',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'document' => 
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
    'ResourceGroup' => 
    array (
      'class' => 'modResourceGroup',
      'key' => 'id',
      'local' => 'document_group',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Resource' => 
    array (
      'class' => 'modResource',
      'key' => 'id',
      'local' => 'document',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
