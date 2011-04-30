<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modAccessActionDom']= array (
  'package' => 'modx',
  'version' => '1.1',
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
