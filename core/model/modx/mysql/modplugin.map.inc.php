<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modPlugin']= array (
  'package' => 'modx',
  'table' => 'site_plugins',
  'fields' => 
  array (
    'cache_type' => 0,
    'plugincode' => '',
    'locked' => 0,
    'properties' => NULL,
    'disabled' => 0,
    'moduleguid' => '',
  ),
  'fieldMeta' => 
  array (
    'cache_type' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'plugincode' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'locked' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => true,
    ),
    'disabled' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'moduleguid' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fk',
    ),
  ),
  'composites' => 
  array (
    'PluginEvents' => 
    array (
      'class' => 'modPluginEvent',
      'local' => 'id',
      'foreign' => 'pluginid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'name' => 
      array (
        'invalid' => 
        array (
          'type' => 'preg_match',
          'rule' => '/(?=^[a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff_-]+$)/',
          'message' => 'plugin_err_invalid_name',
        ),
      ),
    ),
  ),
);
