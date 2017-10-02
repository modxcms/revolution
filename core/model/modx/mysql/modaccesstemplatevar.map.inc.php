<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessTemplateVar']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'access_templatevars',
  'extends' => 'modAccessElement',
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
      'class' => 'modTemplateVar',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
