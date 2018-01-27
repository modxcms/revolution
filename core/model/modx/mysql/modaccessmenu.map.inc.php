<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessMenu']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'access_menus',
  'extends' => 'modAccess',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modMenu',
      'local' => 'target',
      'foreign' => 'text',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
