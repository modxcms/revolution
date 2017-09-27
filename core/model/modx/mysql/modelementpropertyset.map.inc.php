<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modElementPropertySet']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'element_property_sets',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'element' => 0,
    'element_class' => '',
    'property_set' => 0,
  ),
  'fieldMeta' => 
  array (
    'element' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'element_class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
    'property_set' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
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
        'element' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
        'element_class' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
        'property_set' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Element' => 
    array (
      'class' => 'modElement',
      'local' => 'element',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'PropertySet' => 
    array (
      'class' => 'modPropertySet',
      'local' => 'property_set',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
