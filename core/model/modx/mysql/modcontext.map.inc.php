<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modContext']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'context',
  'extends' => 'modAccessibleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'key' => NULL,
    'name' => NULL,
    'description' => NULL,
    'rank' => 0,
  ),
  'fieldMeta' => 
  array (
    'key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
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
        'key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
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
  'composites' => 
  array (
    'ContextResources' => 
    array (
      'class' => 'modContextResource',
      'local' => 'key',
      'foreign' => 'context_key',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'ContextSettings' => 
    array (
      'class' => 'modContextSetting',
      'local' => 'key',
      'foreign' => 'context_key',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'SourceElements' => 
    array (
      'class' => 'sources.modMediaSourceElement',
      'local' => 'key',
      'foreign' => 'context_key',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Acls' => 
    array (
      'class' => 'modAccessContext',
      'local' => 'key',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'key' => 
      array (
        'key' => 
        array (
          'type' => 'preg_match',
          'rule' => '/^[a-zA-Z\\x7f-\\xff][a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff]*$/',
          'message' => 'context_err_ns_key',
        ),
      ),
    ),
  ),
);
