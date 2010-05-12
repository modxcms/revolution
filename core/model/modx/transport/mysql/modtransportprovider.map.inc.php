<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
$xpdo_meta_map['modTransportProvider']= array (
  'package' => 'modx.transport',
  'table' => 'transport_providers',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'service_url' => NULL,
    'api_key' => '',
    'created' => NULL,
    'updated' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
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
    'api_key' => 
    array (
      'dbtype' => 'varchar',
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
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
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
