<?php
$xpdo_meta_map['PersonPhone']= array (
  'package' => 'sample',
  'version' => '1.1',
  'table' => 'person_phone',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'person' => NULL,
    'phone' => NULL,
    'is_primary' => 0,
  ),
  'fieldMeta' => 
  array (
    'person' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'phone' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'is_primary' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'person' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
        'phone' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Phone' => 
    array (
      'class' => 'Phone',
      'local' => 'phone',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'aggregates' => 
  array (
    'Person' => 
    array (
      'class' => 'Person',
      'local' => 'person',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
