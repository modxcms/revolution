<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modDashboardWidgetPlacement']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'dashboard_widget_placement',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'user' => 0,
    'dashboard' => 0,
    'widget' => 0,
    'rank' => 0,
    'size' => 'half',
  ),
  'fieldMeta' => 
  array (
    'user' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'dashboard' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'widget' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'size' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => 'half',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'columns' => 
      array (
        'user' =>
        array (
          'collation' => 'A',
          'null' => false,
        ),
        'dashboard' =>
        array (
          'collation' => 'A',
          'null' => false,
        ),
        'widget' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'rank' => 
    array (
      'alias' => 'rank',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'rank' => 
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
    'Dashboard' => 
    array (
      'class' => 'modDashboard',
      'local' => 'dashboard',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Widget' => 
    array (
      'class' => 'modDashboardWidget',
      'local' => 'widget',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'User' =>
    array (
      'class' => 'modUser',
      'local' => 'user',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
