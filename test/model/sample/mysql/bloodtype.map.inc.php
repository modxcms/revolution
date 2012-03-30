<?php
$xpdo_meta_map['BloodType']= array (
  'package' => 'sample',
  'version' => '1.1',
  'table' => 'blood_types',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'type' => NULL,
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
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
        'type' => 
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
      'local' => 'type',
      'foreign' => 'blood_type',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
  ),
);
