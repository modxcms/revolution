<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modEvent']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'system_eventnames',
  'fields' => 
  array (
    'name' => '',
    'service' => 0,
    'groupname' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'service' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'groupname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
  'aggregates' => 
  array (
    'PluginEvents' => 
    array (
      'class' => 'modPluginEvent',
      'local' => 'name',
      'foreign' => 'event',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
