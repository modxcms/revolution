<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modEventLog']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'event_log',
  'fields' => 
  array (
    'eventid' => 0,
    'createdon' => 0,
    'type' => 1,
    'user' => 0,
    'usertype' => 0,
    'source' => '',
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
    'eventid' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'type' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'user' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'usertype' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'source' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
    ),
  ),
  'indexes' => 
  array (
    'user' => 
    array (
      'alias' => 'user',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'user' => 
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
    'Event' => 
    array (
      'class' => 'modEvent',
      'key' => 'id',
      'local' => 'eventid',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
