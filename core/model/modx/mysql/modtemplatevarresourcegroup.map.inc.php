<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modTemplateVarResourceGroup']= array (
  'package' => 'modx',
  'table' => 'site_tmplvar_access',
  'fields' => 
  array (
    'tmplvarid' => 0,
    'documentgroup' => 0,
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
    ),
    'documentgroup' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
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
    'modResourceGroup' => 
    array (
      'class' => 'modResourceGroup',
      'key' => 'id',
      'local' => 'documentgroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modTemplateVarResourceGroup']['aggregates']= array_merge($xpdo_meta_map['modTemplateVarResourceGroup']['aggregates'], array_change_key_case($xpdo_meta_map['modTemplateVarResourceGroup']['aggregates']));
$xpdo_meta_map['modtemplatevarresourcegroup']= & $xpdo_meta_map['modTemplateVarResourceGroup'];
