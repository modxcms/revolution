<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modContextResource']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'context_resource',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'context_key' => NULL,
    'resource' => NULL,
  ),
  'fieldMeta' => 
  array (
    'context_key' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'resource' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
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
      'columns' => 
      array (
        'context_key' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
        'resource' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Context' => 
    array (
      'class' => 'modContext',
      'local' => 'context_key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Resource' => 
    array (
      'class' => 'modResource',
      'local' => 'resource',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
