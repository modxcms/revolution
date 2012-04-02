<?php
/**
 * @package modx
 * @subpackage sources.sqlsrv
 */
$xpdo_meta_map['modMediaSourceElement']= array (
  'package' => 'modx.sources',
  'version' => '1.1',
  'table' => 'media_sources_tvs',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'source' => 0,
    'object' => 0,
    'object_class' => 'modTemplateVar',
    'context_key' => 'web',
  ),
  'fieldMeta' => 
  array (
    'source' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'object' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'object_class' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'modTemplateVar',
      'index' => 'pk',
    ),
    'context_key' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'web',
      'index' => 'pk',
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
        'source' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'object' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'object_class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'context_key' => 
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
    'Source' => 
    array (
      'class' => 'sources.modMediaSource',
      'local' => 'source',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Element' => 
    array (
      'class' => 'modElement',
      'local' => 'object',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Context' => 
    array (
      'class' => 'modContext',
      'local' => 'context_key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
