<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modElementPropertySet']= array (
  'package' => 'modx',
  'table' => 'element_property_sets',
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
