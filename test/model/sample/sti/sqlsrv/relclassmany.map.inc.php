<?php
$xpdo_meta_map['relClassMany']= array (
  'package' => 'sample.sti',
  'version' => '1.1',
  'table' => 'sti_related_many',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'field1' => NULL,
    'field2' => NULL,
    'date_modified' => 'CURRENT_TIMESTAMP',
    'fkey' => NULL,
  ),
  'fieldMeta' => 
  array (
    'field1' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '200',
      'phptype' => 'string',
      'null' => false,
    ),
    'field2' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
    ),
    'date_modified' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
    'fkey' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'fk',
    ),
  ),
  'indexes' => 
  array (
    'fkey' => 
    array (
      'alias' => 'fkey',
      'primary' => false,
      'unique' => false,
      'columns' => 
      array (
        'fkey' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'relParent' => 
    array (
      'class' => 'baseClass',
      'local' => 'fkey',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
