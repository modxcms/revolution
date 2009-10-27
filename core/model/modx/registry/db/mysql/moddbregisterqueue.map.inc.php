<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
$xpdo_meta_map['modDbRegisterQueue']= array (
  'package' => 'modx.registry.db',
  'table' => 'register_queues',
  'fields' => 
  array (
    'name' => NULL,
    'options' => NULL,
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
    'options' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'array',
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
