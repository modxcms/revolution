<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessAction']= array (
  'package' => 'modx',
  'table' => 'access_actions',
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modAction',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessAction']['aggregates']= array_merge($xpdo_meta_map['modAccessAction']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessAction']['aggregates']));
$xpdo_meta_map['modaccessaction']= & $xpdo_meta_map['modAccessAction'];
