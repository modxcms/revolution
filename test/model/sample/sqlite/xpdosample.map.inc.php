<?php
$xpdo_meta_map['xPDOSample']= array (
  'package' => 'sample',
  'version' => '1.1',
  'table' => 'xpdosample',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'parent' => 0,
    'unique_varchar' => NULL,
    'varchar' => NULL,
    'text' => NULL,
    'timestamp' => 'CURRENT_TIMESTAMP',
    'unix_timestamp' => 0,
    'date_time' => NULL,
    'date' => NULL,
    'enum' => NULL,
    'password' => NULL,
    'integer' => NULL,
    'float' => '1.01230',
    'boolean' => NULL,
  ),
  'fieldMeta' => 
  array (
    'parent' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'unique_varchar' => 
    array (
      'dbtype' => 'varchar',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'varchar' => 
    array (
      'dbtype' => 'varchar',
      'phptype' => 'string',
      'null' => false,
    ),
    'text' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'timestamp' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
    'unix_timestamp' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'date_time' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'date' => 
    array (
      'dbtype' => 'date',
      'phptype' => 'date',
      'null' => true,
    ),
    'enum' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'\',\'T\',\'F\'',
      'phptype' => 'string',
      'null' => false,
    ),
    'password' => 
    array (
      'dbtype' => 'varchar',
      'phptype' => 'password',
      'null' => false,
    ),
    'integer' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
    ),
    'float' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,5',
      'phptype' => 'float',
      'null' => false,
      'default' => '1.01230',
    ),
    'boolean' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
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
        'id' => 
        array (
        ),
      ),
    ),
    'unique_varchar' => 
    array (
      'alias' => 'unique_varchar',
      'primary' => false,
      'unique' => true,
      'columns' => 
      array (
        'unique_varchar' => 
        array (
        ),
      ),
    ),
  ),
);
