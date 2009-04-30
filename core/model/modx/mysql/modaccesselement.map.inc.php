<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessElement']= array (
  'package' => 'modx',
  'table' => 'access_elements',
  'fields' => 
  array (
    'context_key' => '',
  ),
  'fieldMeta' => 
  array (
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fk',
    ),
  ),
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modElement',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessElement']['aggregates']= array_merge($xpdo_meta_map['modAccessElement']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessElement']['aggregates']));
$xpdo_meta_map['modaccesselement']= & $xpdo_meta_map['modAccessElement'];
