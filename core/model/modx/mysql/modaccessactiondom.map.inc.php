<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessActionDom']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'access_actiondom',
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
      'class' => 'modActionDom',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
