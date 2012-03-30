<?php
$xpdo_meta_map['Item']= array (
  'package' => 'sample',
  'version' => '1.1',
  'table' => 'items',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'color' => 'green',
    'description' => NULL,
    'date_modified' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fk',
    ),
    'color' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => 'green',
      'index' => 'fk',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'date_modified' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'primary' => false,
      'unique' => false,
      'columns' => 
      array (
        'name' => 
        array (
        ),
      ),
    ),
    'color' => 
    array (
      'primary' => false,
      'unique' => false,
      'columns' => 
      array (
        'color' => 
        array (
        ),
      ),
    ),
  ),
);
