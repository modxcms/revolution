<?php
/**
 * @package modx
 * @subpackage transport.sqlsrv
 */
$xpdo_meta_map['modTransportProvider']= array (
  'package' => 'modx.transport',
  'version' => '1.1',
  'table' => 'transport_providers',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'service_url' => NULL,
    'username' => '',
    'api_key' => '',
    'created' => NULL,
    'updated' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
    ),
    'service_url' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '512',
      'phptype' => 'string',
    ),
    'username' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'api_key' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
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
      'dbtype' => 'datetime',
      'phptype' => 'timestamp',
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
