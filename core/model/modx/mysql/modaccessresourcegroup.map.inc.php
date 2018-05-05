<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessResourceGroup']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'access_resource_groups',
  'extends' => 'modAccess',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'context_key' => '',
  ),
  'fieldMeta' => 
  array (
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fk',
    ),
  ),
  'indexes' => 
  array (
    'context_key' => 
    array (
      'alias' => 'context_key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'context_key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'principal_class' => 
    array (
      'alias' => 'principal_class',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'principal_class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'target' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'principal' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'authority' => 
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
    'Target' => 
    array (
      'class' => 'modResourceGroup',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Context' => 
    array (
      'class' => 'modContext',
      'local' => 'context_key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
