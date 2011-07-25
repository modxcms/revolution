<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modDashboard']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'dashboard',
  'fields' => 
  array (
    'name' => '',
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
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
);
