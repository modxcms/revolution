<?php
/**
 * @package modx
 * @subpackage sources.sqlsrv
 */
$xpdo_meta_map['modMediaSource']= array (
  'package' => 'modx.sources',
  'version' => '1.1',
  'table' => 'media_sources',
  'fields' => 
  array (
    'name' => '',
    'description' => NULL,
    'class_key' => 'sources.modFileMediaSource',
    'properties' => NULL,
    'is_stream' => 1,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'null' => true,
    ),
    'class_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'sources.modFileMediaSource',
      'index' => 'index',
    ),
    'properties' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'array',
      'null' => true,
    ),
    'is_stream' => 
    array (
      'dbtype' => 'bit',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
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
    'class_key' => 
    array (
      'alias' => 'class_key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'class_key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'is_stream' => 
    array (
      'alias' => 'is_stream',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'is_stream' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'SourceElement' => 
    array (
      'class' => 'sources.modMediaSourceElement',
      'local' => 'id',
      'foreign' => 'source',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Chunks' => 
    array (
      'class' => 'modChunk',
      'local' => 'id',
      'foreign' => 'source',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Plugins' => 
    array (
      'class' => 'modPlugin',
      'local' => 'id',
      'foreign' => 'source',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Snippets' => 
    array (
      'class' => 'modSnippet',
      'local' => 'id',
      'foreign' => 'source',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Templates' => 
    array (
      'class' => 'modTemplate',
      'local' => 'id',
      'foreign' => 'source',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'TemplateVars' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'id',
      'foreign' => 'source',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
