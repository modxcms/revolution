<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modResourceGroup']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'documentgroup_names',
  'fields' => 
  array (
    'name' => '',
    'private_memgroup' => 0,
    'private_webgroup' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'private_memgroup' => 
    array (
      'dbtype' => 'bit',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'private_webgroup' => 
    array (
      'dbtype' => 'bit',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'ResourceGroupResources' => 
    array (
      'class' => 'modResourceGroupResource',
      'local' => 'id',
      'foreign' => 'document_group',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Acls' => 
    array (
      'class' => 'modAccessResourceGroup',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
