<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modAccessContext']= array (
  'package' => 'modx',
  'version' => '1.1',
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
