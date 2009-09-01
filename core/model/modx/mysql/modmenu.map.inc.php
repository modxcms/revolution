<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modMenu']= array (
  'package' => 'modx',
  'table' => 'menus',
  'fields' => 
  array (
    'parent' => 0,
    'action' => 0,
    'text' => NULL,
    'description' => '',
    'icon' => '',
    'menuindex' => 0,
    'params' => NULL,
    'handler' => NULL,
  ),
  'fieldMeta' => 
  array (
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'action' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'text' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'icon' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'menuindex' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'params' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'handler' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'Action' => 
    array (
      'class' => 'modAction',
      'local' => 'action',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Parent' => 
    array (
      'class' => 'modMenu',
      'local' => 'parent',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Children' => 
    array (
      'class' => 'modMenu',
      'local' => 'id',
      'foreign' => 'parent',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'composites' => 
  array (
    'Acls' => 
    array (
      'class' => 'modAccessMenu',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modMenu']['aggregates']= array_merge($xpdo_meta_map['modMenu']['aggregates'], array_change_key_case($xpdo_meta_map['modMenu']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modMenu']['composites']= array_merge($xpdo_meta_map['modMenu']['composites'], array_change_key_case($xpdo_meta_map['modMenu']['composites']));
$xpdo_meta_map['modmenu']= & $xpdo_meta_map['modMenu'];
