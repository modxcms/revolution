<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
$xpdo_meta_map['modTransportPackage']= array (
  'package' => 'modx.transport',
  'table' => 'transport_packages',
  'fields' => 
  array (
    'signature' => NULL,
    'created' => NULL,
    'updated' => NULL,
    'installed' => NULL,
    'state' => 1,
    'workspace' => 0,
    'provider' => 0,
    'disabled' => 0,
    'source' => NULL,
    'manifest' => NULL,
    'attributes' => NULL,
  ),
  'fieldMeta' => 
  array (
    'signature' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
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
    'installed' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
    ),
    'state' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'workspace' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
    'provider' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
    'disabled' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'source' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
    ),
    'manifest' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
    ),
    'attributes' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'array',
    ),
  ),
  'aggregates' => 
  array (
    'Workspace' => 
    array (
      'class' => 'modWorkspace',
      'local' => 'workspace',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Provider' => 
    array (
      'class' => 'transport.modTransportProvider',
      'local' => 'provider',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
