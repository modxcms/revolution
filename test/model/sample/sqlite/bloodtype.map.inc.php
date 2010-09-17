<?php
$xpdo_meta_map['BloodType']= array (
  'package' => 'sample',
  'table' => 'blood_types',
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
