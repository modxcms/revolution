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
    'modTemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'key' => 'id',
      'local' => 'tmplvarid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modResource' => 
    array (
      'class' => 'modResource',
      'key' => 'id',
      'local' => 'contentid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modTemplateVarResource']['aggregates']= array_merge($xpdo_meta_map['modTemplateVarResource']['aggregates'], array_change_key_case($xpdo_meta_map['modTemplateVarResource']['aggregates']));
$xpdo_meta_map['modtemplatevarresource']= & $xpdo_meta_map['modTemplateVarResource'];
