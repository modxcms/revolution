<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modPluginEvent']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'site_plugin_events',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'pluginid' => 0,
    'event' => '',
    'priority' => 0,
    'propertyset' => 0,
  ),
  'fieldMeta' => 
  array (
    'pluginid' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'event' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
    'priority' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'propertyset' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
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
        'pluginid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'event' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'priority' => 
    array (
      'alias' => 'priority',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'priority' => 
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
    'Plugin' => 
    array (
      'class' => 'modPlugin',
      'local' => 'pluginid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Event' => 
    array (
      'class' => 'modEvent',
      'local' => 'event',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'PropertySet' => 
    array (
      'class' => 'modPropertySet',
      'local' => 'propertyset',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
