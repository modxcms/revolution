<?php
/**
 * @package modx
 * @subpackage registry.db.sqlsrv
 */
$xpdo_meta_map['modDbRegisterQueue']= array (
  'package' => 'modx.registry.db',
  'version' => '1.1',
  'table' => 'register_queues',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'name' => NULL,
    'options' => NULL,
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
    'options' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'array',
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
  ),
  'composites' => 
  array (
    'Topics' => 
    array (
      'class' => 'registry.db.modDbRegisterTopic',
      'local' => 'id',
      'foreign' => 'queue',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
