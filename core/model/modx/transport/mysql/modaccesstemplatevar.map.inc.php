<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
$xpdo_meta_map['modAccessTemplateVar']= array (
  'package' => 'modx',
  'table' => 'access_templatevars',
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
