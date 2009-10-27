<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
$xpdo_meta_map['modDbRegisterMessage']= array (
  'package' => 'modx.registry.db',
  'table' => 'register_messages',
  'fields' => 
  array (
    'topic' => NULL,
    'id' => NULL,
    'created' => NULL,
    'valid' => NULL,
    'accessed' => NULL,
    'accesses' => 0,
    'expires' => 0,
    'payload' => NULL,
    'kill' => 0,
  ),
  'fieldMeta' => 
  array (
    'topic' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'id' => 
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
      'index' => 'index',
    ),
    'valid' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'index' => 'index',
    ),
    'accessed' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
      'index' => 'index',
    ),
    'accesses' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'expires' => 
    array (
      'dbtype' => 'integer',
      'precision' => '20',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'payload' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => false,
    ),
    'kill' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Topic' => 
    array (
      'class' => 'registry.db.modDbRegisterTopic',
      'local' => 'topic',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
