<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
$xpdo_meta_map['modTransportProvider']= array (
  'package' => 'modx.transport',
  'version' => '1.1',
  'table' => 'transport_providers',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'service_url' => NULL,
    'username' => '',
    'api_key' => '',
    'created' => NULL,
    'updated' => NULL,
    'active' => 1,
    'priority' => 10,
    'properties' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'service_url' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
    ),
    'username' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'api_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'created' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
    ),
    'updated' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
    'priority' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 10,
      'index' => 'index',
    ),
    'properties' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'json',
      'null' => false,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'api_key' => 
    array (
      'alias' => 'api_key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'api_key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'username' => 
    array (
      'alias' => 'username',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'username' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'active' => 
    array (
      'alias' => 'active',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'active' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'priority' => 
    array (
      'alias' => 'priority',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'priority' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Packages' => 
    array (
      'class' => 'transport.modTransportPackage',
      'local' => 'id',
      'foreign' => 'provider',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
