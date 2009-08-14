<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessPolicy']= array (
  'package' => 'modx',
  'table' => 'access_policies',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'parent' => 0,
    'class' => '',
    'data' => '{}',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'data' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'default' => '{}',
    ),
  ),
  'aggregates' => 
  array (
    'Parent' => 
    array (
      'class' => 'modAccessPolicy',
      'local' => 'parent',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
  'composites' => 
  array (
    'Children' => 
    array (
      'class' => 'modAccessPolicy',
      'local' => 'id',
      'foreign' => 'parent',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
    'Permissions' => 
    array (
      'class' => 'modAccessPermission',
      'local' => 'id',
      'foreign' => 'policy',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessPolicy']['aggregates']= array_merge($xpdo_meta_map['modAccessPolicy']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessPolicy']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessPolicy']['composites']= array_merge($xpdo_meta_map['modAccessPolicy']['composites'], array_change_key_case($xpdo_meta_map['modAccessPolicy']['composites']));
$xpdo_meta_map['modaccesspolicy']= & $xpdo_meta_map['modAccessPolicy'];
