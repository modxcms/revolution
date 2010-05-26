<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modContext']= array (
  'package' => 'modx',
  'table' => 'context',
  'fields' => 
  array (
    'key' => NULL,
    'description' => NULL,
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
    'description' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
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
