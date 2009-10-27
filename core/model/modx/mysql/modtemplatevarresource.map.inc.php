<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modTemplateVarResource']= array (
  'package' => 'modx',
  'table' => 'site_tmplvar_contentvalues',
  'fields' => 
  array (
    'tmplvarid' => 0,
    'contentid' => 0,
    'value' => NULL,
  ),
  'fieldMeta' => 
  array (
    'tmplvarid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'contentid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'TemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'tmplvarid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Resource' => 
    array (
      'class' => 'modResource',
      'local' => 'contentid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
