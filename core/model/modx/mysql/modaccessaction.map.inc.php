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
