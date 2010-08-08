<?php
$xpdo_meta_map['PersonPhone']= array (
  'package' => 'sample',
  'table' => 'person_phone',
  'fields' => 
  array (
    'person' => NULL,
    'phone' => NULL,
    'is_primary' => '0',
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
      'dbtype' => 'binary',
      'precision' => '1',
      'phptype' => 'binary',
      'null' => false,
      'default' => '0',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
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
);
