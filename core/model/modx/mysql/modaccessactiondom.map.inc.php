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
