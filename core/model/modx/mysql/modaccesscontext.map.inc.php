<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessContext']= array (
  'package' => 'modx',
  'table' => 'access_context',
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modContext',
      'local' => 'target',
      'foreign' => 'key',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessContext']['aggregates']= array_merge($xpdo_meta_map['modAccessContext']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessContext']['aggregates']));
$xpdo_meta_map['modaccesscontext']= & $xpdo_meta_map['modAccessContext'];
