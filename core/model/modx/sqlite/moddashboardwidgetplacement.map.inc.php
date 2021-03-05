<?php
/**
 * @package modx
 * @subpackage sqlite
 */
$xpdo_meta_map['modDashboardWidgetPlacement']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'dashboard_widget_placement',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'dashboard' => 0,
    'widget' => 0,
    'rank' => 0,
  ),
  'fieldMeta' => 
  array (
    'dashboard' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'widget' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'rank' => 
    array (
      'dbtype' => 'integer',
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
      'columns' => 
      array (
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
  ),
);
