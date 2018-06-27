<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modClassMap']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'class_map',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
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
      'precision' => '191',
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
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core:resource',
    ),
  ),
  'indexes' => 
  array (
    'class' => 
    array (
      'alias' => 'class',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'parent_class' => 
    array (
      'alias' => 'parent_class',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'parent_class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'name_field' => 
    array (
      'alias' => 'name_field',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name_field' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
