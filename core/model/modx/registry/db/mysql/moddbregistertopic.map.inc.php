<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
$xpdo_meta_map['modDbRegisterTopic']= array (
  'package' => 'modx.registry.db',
  'table' => 'register_topics',
  'fields' => 
  array (
    'queue' => NULL,
    'name' => NULL,
    'created' => NULL,
    'updated' => NULL,
    'options' => NULL,
  ),
  'fieldMeta' => 
  array (
    'queue' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'fk',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'fk',
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
    'options' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'array',
    ),
  ),
  'aggregates' => 
  array (
    'Queue' => 
    array (
      'class' => 'registry.db.modDbRegisterQueue',
      'local' => 'queue',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'composites' => 
  array (
    'Messages' => 
    array (
      'class' => 'registry.db.modDbRegisterMessage',
      'local' => 'id',
      'foreign' => 'topic',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
