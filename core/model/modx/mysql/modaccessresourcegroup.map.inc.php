<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessResourceGroup']= array (
  'package' => 'modx',
  'table' => 'access_resource_groups',
  'fields' => 
  array (
    'context_key' => '',
  ),
  'fieldMeta' => 
  array (
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fk',
    ),
  ),
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modResourceGroup',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessResourceGroup']['aggregates']= array_merge($xpdo_meta_map['modAccessResourceGroup']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessResourceGroup']['aggregates']));
$xpdo_meta_map['modaccessresourcegroup']= & $xpdo_meta_map['modAccessResourceGroup'];
