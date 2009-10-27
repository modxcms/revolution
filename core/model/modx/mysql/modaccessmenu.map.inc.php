<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessMenu']= array (
  'package' => 'modx',
  'table' => 'access_menus',
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
