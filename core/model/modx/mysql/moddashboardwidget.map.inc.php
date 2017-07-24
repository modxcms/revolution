<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modDashboardWidget']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'dashboard_widget',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'name' => '',
    'description' => NULL,
    'type' => NULL,
    'content' => NULL,
    'namespace' => '',
    'lexicon' => 'core:dashboards',
    'size' => 'half',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
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
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'content' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'lexicon' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core:dashboards',
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
    'type' => 
    array (
      'alias' => 'type',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'type' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'namespace' => 
    array (
      'alias' => 'namespace',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'namespace' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'lexicon' => 
    array (
      'alias' => 'lexicon',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'lexicon' => 
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
      'foreign' => 'widget',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Namespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
