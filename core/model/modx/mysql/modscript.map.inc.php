<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modScript']= array (
  'package' => 'modx',
  'table' => 'site_script',
  'fields' => 
  array (
    'name' => '',
    'description' => '',
    'editor_type' => 0,
    'category' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'editor_type' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'category' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
  ),
  'aggregates' => 
  array (
    'modCategory' => 
    array (
      'class' => 'modCategory',
      'key' => 'id',
      'local' => 'category',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modScript']['aggregates']= array_merge($xpdo_meta_map['modScript']['aggregates'], array_change_key_case($xpdo_meta_map['modScript']['aggregates']));
$xpdo_meta_map['modscript']= & $xpdo_meta_map['modScript'];
