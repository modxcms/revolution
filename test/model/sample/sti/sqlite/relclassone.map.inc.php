<?php
$xpdo_meta_map['relClassOne']= array (
  'package' => 'sample.sti',
  'version' => '1.1',
  'table' => 'sti_related_one',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'field1' => NULL,
    'field2' => NULL,
  ),
  'fieldMeta' => 
  array (
    'field1' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'field2' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'relParent' => 
    array (
      'class' => 'baseClass',
      'local' => 'id',
      'foreign' => 'fkey',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
);
