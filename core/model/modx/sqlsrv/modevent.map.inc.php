<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modEvent']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'system_eventnames',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'name' => NULL,
    'service' => 0,
    'groupname' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'service' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'groupname' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
