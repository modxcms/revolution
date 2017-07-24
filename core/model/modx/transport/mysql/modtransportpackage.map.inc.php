<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
$xpdo_meta_map['modTransportPackage']= array (
  'package' => 'modx.transport',
  'version' => '1.1',
  'table' => 'transport_packages',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
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
    'package_name' => NULL,
    'metadata' => NULL,
    'version_major' => 0,
    'version_minor' => 0,
    'version_patch' => 0,
    'release' => '',
    'release_index' => 0,
  ),
  'fieldMeta' => 
  array (
    'signature' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
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
    'package_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'metadata' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
    ),
    'version_major' => 
    array (
      'dbtype' => 'smallint',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'version_minor' => 
    array (
      'dbtype' => 'smallint',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'version_patch' => 
    array (
      'dbtype' => 'smallint',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'release' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'release_index' => 
    array (
      'dbtype' => 'smallint',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'signature' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'workspace' => 
    array (
      'alias' => 'workspace',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'workspace' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'provider' => 
    array (
      'alias' => 'provider',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'provider' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'disabled' => 
    array (
      'alias' => 'disabled',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'disabled' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'package_name' => 
    array (
      'alias' => 'package_name',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'package_name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'version_major' => 
    array (
      'alias' => 'version_major',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'version_major' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'version_minor' => 
    array (
      'alias' => 'version_minor',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'version_minor' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'version_patch' => 
    array (
      'alias' => 'version_patch',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'version_patch' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'release' => 
    array (
      'alias' => 'release',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'release' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'release_index' => 
    array (
      'alias' => 'release_index',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'release_index' => 
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
