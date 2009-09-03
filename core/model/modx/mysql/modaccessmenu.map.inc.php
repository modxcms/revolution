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
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessMenu']['aggregates']= array_merge($xpdo_meta_map['modAccessMenu']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessMenu']['aggregates']));
$xpdo_meta_map['modaccessmenu']= & $xpdo_meta_map['modAccessMenu'];
