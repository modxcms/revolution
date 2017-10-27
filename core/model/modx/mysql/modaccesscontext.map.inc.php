<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessContext']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'access_context',
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
      'class' => 'modContext',
      'local' => 'target',
      'foreign' => 'key',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
