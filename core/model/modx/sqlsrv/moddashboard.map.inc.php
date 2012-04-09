<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modDashboard']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'dashboard',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'hide_trees' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
    ),
    'hide_trees' => 
    array (
      'dbtype' => 'bit',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
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
    'hide_trees' => 
    array (
      'alias' => 'hide_trees',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'hide_trees' => 
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
    'Placements' => 
    array (
      'class' => 'modDashboardWidgetPlacement',
      'local' => 'id',
      'foreign' => 'dashboard',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'UserGroups' => 
    array (
      'class' => 'modUserGroup',
      'local' => 'id',
      'foreign' => 'dashboard',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
