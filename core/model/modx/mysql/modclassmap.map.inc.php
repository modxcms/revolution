<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modClassMap']= array (
  'package' => 'modx',
  'table' => 'class_map',
  'fields' => 
  array (
    'class' => '',
    'parent_class' => '',
    'name_field' => 'name',
    'path' => '',
    'lexicon' => 'core:resource',
  ),
  'fieldMeta' => 
  array (
    'class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '120',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'parent_class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '120',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'name_field' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => 'name',
      'index' => 'index',
    ),
    'path' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'default' => '',
    ),
    'lexicon' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core:resource',
    ),
  ),
);
