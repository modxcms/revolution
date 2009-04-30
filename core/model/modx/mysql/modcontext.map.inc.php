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
  'aggregates' => 
  array (
    'modResource' => 
    array (
      'class' => 'modResource',
      'key' => 'key',
      'local' => 'key',
      'foreign' => 'context_key',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'composites' => 
  array (
    'modContextResource' => 
    array (
      'class' => 'modContextResource',
      'local' => 'key',
      'foreign' => 'context_key',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modContextSetting' => 
    array (
      'class' => 'modContextSetting',
      'key' => 'key',
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
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modContext']['aggregates']= array_merge($xpdo_meta_map['modContext']['aggregates'], array_change_key_case($xpdo_meta_map['modContext']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modContext']['composites']= array_merge($xpdo_meta_map['modContext']['composites'], array_change_key_case($xpdo_meta_map['modContext']['composites']));
$xpdo_meta_map['modcontext']= & $xpdo_meta_map['modContext'];
