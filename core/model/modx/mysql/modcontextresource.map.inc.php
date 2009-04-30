<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modContextResource']= array (
  'package' => 'modx',
  'table' => 'context_resource',
  'fields' => 
  array (
    'context_key' => NULL,
    'resource' => NULL,
  ),
  'fieldMeta' => 
  array (
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'resource' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
  ),
  'aggregates' => 
  array (
    'modContext' => 
    array (
      'class' => 'modContext',
      'local' => 'context_key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modResource' => 
    array (
      'class' => 'modResource',
      'local' => 'resource',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modContextResource']['aggregates']= array_merge($xpdo_meta_map['modContextResource']['aggregates'], array_change_key_case($xpdo_meta_map['modContextResource']['aggregates']));
$xpdo_meta_map['modcontextresource']= & $xpdo_meta_map['modContextResource'];
