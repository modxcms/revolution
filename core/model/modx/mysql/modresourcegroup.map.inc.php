<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modResourceGroup']= array (
  'package' => 'modx',
  'table' => 'documentgroup_names',
  'fields' => 
  array (
    'name' => '',
    'private_memgroup' => 0,
    'private_webgroup' => 0,
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
      'index' => 'unique',
    ),
    'private_memgroup' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'private_webgroup' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'modResourceGroupResource' => 
    array (
      'class' => 'modResourceGroupResource',
      'local' => 'id',
      'foreign' => 'document_group',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Acls' => 
    array (
      'class' => 'modAccessResourceGroup',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modResourceGroup']['composites']= array_merge($xpdo_meta_map['modResourceGroup']['composites'], array_change_key_case($xpdo_meta_map['modResourceGroup']['composites']));
$xpdo_meta_map['modresourcegroup']= & $xpdo_meta_map['modResourceGroup'];
