<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modActionField']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'actions_fields',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'action' => '',
    'name' => '',
    'type' => 'field',
    'tab' => '',
    'form' => '',
    'other' => '',
    'rank' => 0,
  ),
  'fieldMeta' => 
  array (
    'action' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'type' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'field',
      'index' => 'index',
    ),
    'tab' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'form' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'other' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'action' => 
    array (
      'alias' => 'action',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'action' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'type' => 
    array (
      'alias' => 'type',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'type' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'tab' => 
    array (
      'alias' => 'tab',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'tab' => 
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
    'Action' => 
    array (
      'class' => 'modAction',
      'local' => 'action',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
