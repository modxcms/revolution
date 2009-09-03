<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessActionDom']= array (
  'package' => 'modx',
  'table' => 'access_actiondom',
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modActionDom',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessActionDom']['aggregates']= array_merge($xpdo_meta_map['modAccessActionDom']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessActionDom']['aggregates']));
$xpdo_meta_map['modaccessactiondom']= & $xpdo_meta_map['modAccessActionDom'];
